function createSelection(field, start, end) {
	if( field.createTextRange ) {
		var selRange = field.createTextRange();
		selRange.collapse(true);
		selRange.moveStart('character', start);
		selRange.moveEnd('character', end);
		selRange.select();
	} else if( field.setSelectionRange ) {
		field.setSelectionRange(start, end);
	} else if( field.selectionStart ) {
		field.selectionStart = start;
		field.selectionEnd = end;
	}
	field.focus();
}


var BaseView = function (options) {
    this.bindings = [];
    Backbone.View.apply(this, [options]);
};

_.extend(BaseView.prototype, Backbone.View.prototype, {

    bindTo: function (model, ev, callback) {
        model.bind(ev, callback, this);
        this.bindings.push({ model: model, ev: ev, callback: callback });
    },

    unbindFromAll: function () {
        _.each(this.bindings, function (binding) {
            binding.model.unbind(binding.ev, binding.callback);
        });
        this.bindings = [];
    },

    dispose: function () {
        this.unbindFromAll(); // this will unbind all events that this view has bound to 
        this.unbind(); // this will unbind all listeners to events from this view. This is probably not necessary because this view will be garbage collected.
        this.remove(); // uses the default Backbone.View.remove() method which removes this.el from the DOM and removes DOM events.
    }

});

BaseView.extend = Backbone.View.extend;


$(document).ready(function() {

var VRuleView = BaseView.extend({
	tagName: 'li',
	template: _.template($('#vRule-template').html()),
	events: {
		'click .remove': 'markForRemoval',
		'click .restore': 'restore',
		'keypress input': 'saveOnEnter',
		'blur input': 'save'
	},
	initialize: function() {
		this.props = {};
		if(arguments.length>0 && typeof(arguments[0]) != 'undefined') {
			this.props.rule = arguments[0].rule;
			this.state = arguments[0].state || '';
		} else {
			this.props.rule = '';
			this.state = '';
		}
		if(this.props.rule.indexOf('[')!==-1) {
			this.props.paramStart = this.props.rule.indexOf('[');
			this.props.paramEnd   = this.props.rule.indexOf(']');
		}
		this.render();
	},
	render: function() {		
		this.$el.html(this.template(this.props));	
		switch(this.state) {	
			case 'removed':
				this.$el.find('.remove').hide();
				this.$el.find('.restore').show();
				this.$el.find('input').css('text-decoration', 'line-through');
				break;
			default: 
				this.$el.find('.remove').show();
				this.$el.find('.restore').hide();
				this.$el.find('input').css('text-decoration', 'none');					
				break;
		}
	},
	onAddToHTML: function() {
		if('paramStart' in this.props) {
			createSelection(this.$el.find('input').get(0), this.props.paramStart+1, this.props.paramEnd);
		}
	},
	markForRemoval: function(e) {
		this.state = 'removed';
		this.$el.find('.remove').hide();
		this.$el.find('.restore').show();
		this.$el.find('input').css('text-decoration', 'line-through');
		e.preventDefault();
	},
	restore: function(e) {
		this.state = '';
		this.$el.find('.remove').show();
		this.$el.find('.restore').hide();
		this.$el.find('input').css('text-decoration', 'none');
		e.preventDefault();
	},
	saveOnEnter: function(e) {
		if(e.keyCode == 13)
			this.save();
	},
	save: function() {
		this.props.rule = this.$el.find('input').val();
	}
	
});
var VGroupTab = BaseView.extend({
	tagName: 'li',	
	template: _.template($('#vGroupTab-template').html()),
	events: {
		'click .remove' : 'markForRemoval',
		'click .restore' : 'restore',
		'dblclick .name': 'edit',
		'keypress input': 'saveOnEnter',
		'blur input': 'saveOnBlur',
		'click .name'   : 'select'
	},
	initialize: function() {
		this.props = {};
		this.state = '';
		this.editing = false;
		if(arguments.length>0 && typeof arguments[0] !== 'undefined') {
			this.props.name = arguments[0].name;
			this.state = arguments[0].state || '';
		} else {
			this.props.name = '';
		}
		this.render();
	},
	edit: function() {
		this.label.hide();
		this.edit_field.show().val(this.props.name).focus();	
		this.editing = true;
	},
	saveOnEnter: function(e) {
		if (e.keyCode == 13) {
			this.save();
		}
	},
	saveOnBlur: function() {
		this.save();
	},
	save: function() {		
		var e = {old_name: this.props.name, new_name: this.edit_field.val()};
		if(typeof(this.props.name) == 'undefined' || this.props.name.length == 0)
			this.props.name = e.new_name;
		else
			this.props.new_name = e.new_name;
		this.render();
		this.trigger('save.vGroupTab', e);	
	},
	render: function() {	
		
		this.$el.html(this.template(this.props));
				
		this.label      = this.$el.find('.name').eq(0);		
		this.edit_field = this.$el.find('input').eq(0);
		this.removeBtn  = this.$el.find('.remove').eq(0);
		this.restoreBtn = this.$el.find('.restore').eq(0);
		
		if(typeof(this.props.name) == 'undefined' || this.props.name.length == 0) {	
			this.edit_field.show().focus();
			this.label.hide();			
			this.editing = true;
		} else {
			this.edit_field.hide();
			this.label.show();
			this.editing = false;
		}
		
		switch(this.state) {	
			case 'removed':
				this.$el.find('.remove').hide();
				this.$el.find('.restore').show();
				this.$el.find('input').css('text-decoration', 'line-through');
				this.$el.find('.name').css('text-decoration', 'line-through');
				break;
			default: 
				this.$el.find('.remove').show();
				this.$el.find('.restore').hide();
				this.$el.find('input').css('text-decoration', 'none');					
				this.$el.find('.name').css('text-decoration', 'none');					
				break;
		}
		
		return this;
	},
	select: function(e) {		
		if(!this.editing) {
			App.form.switchVGroup(this.props.name);
			e.preventDefault();
		}
	},
	
	markForRemoval: function(e) {
		//console.log('removed');
		this.state = 'removed';
		this.$el.find('.remove').hide();
		this.$el.find('.restore').show();
		this.$el.find('.name').css('text-decoration', 'line-through');
		e.preventDefault();
	},
	restore: function(e) {
		this.state = '';
		this.$el.find('.remove').show();
		this.$el.find('.restore').hide();
		this.$el.find('.name').css('text-decoration', 'none');
		e.preventDefault();
	},
	onAddToHTML: function() {
		this.$el.find('input').focus();
	}
});	



var Field = Backbone.Model.extend({	
	idAttribute: 'name',
	initialize: function() {		
		if(arguments.length == 0) {
			this.set('vgroups', [{name: 'create', rules:[]}, {name: 'update', rules:[]}]);
		}
	},
	remove: function() {
		this.set({'state': 'removed'});
	},
	restore: function() {
		this.set({'state': 'restored'});
	},
	isRemoved: function() {
		return (this.get('state') == 'removed');
	}
});

var FieldList = Backbone.Collection.extend({ 
	model: Field,	
	url: '',	
	nextOrder: function() {
		if(!this.length) return 1;
		return this.last().get('order') + 1;
	}	
});

Fields = new FieldList;

var FieldView = BaseView.extend({
	
	tagName: 'tr',
	template: _.template($('#field-template').html()),
	state: 'on',
	events: {
		'click .remove': 'removeModel',
		'click .restore': 'restoreModel',
		'click .fieldTitle': 'edit'
	},
	
	initialize: function() {
		this.model.bind('change', this.render, this);
		_.bindAll(this, 'edit');
		this.identifier = this.id.replace('field-','');
	},
	
	render: function() {
		this.$el.html(this.template(this.model.toJSON()));
		this.input = this.$('.edit');
		if(!this.model.isRemoved()) {		
			this.$el.find('.fieldTitle').css('text-decoration', 'none');
			this.$el.find('.restore').hide();
			this.$el.find('.remove').show();
		} else {			
			this.$el.find('.fieldTitle').css('text-decoration', 'line-through');
			this.$el.find('.restore').show();
			this.$el.find('.remove').hide();
		}
		
		return this;
	},
	
	edit: function() {
		App.fieldEdit(this.model);
	},
	
	removeModel: function(e) {
		this.model.remove();
		e.preventDefault();
	},
	
	restoreModel: function(e) {
		this.model.restore();	
		e.preventDefault();
	}	
	
});

var FormView = BaseView.extend({

	el: $('#form-holder'),
	elements: {},
	vGroupTabs: {},
	vGroupRules: {},
	events: {
		'submit form': 'submit',
		'click a[data-rule]': 'addValidationRule'
	},
	
	initialize: function() {	
		_.each($(this.el).find('input,select,button'), function(e) {			
			if(e.name) {				
				this.elements[e.name] = $(e);
			}
		}, this);
		this.elements['button_field_name'] = $(this.el).find('.button_field_name').eq(0);
		
		_.bindAll(this, 'render'); 		
		
		this.elements.name.on('keyup', this.render);		
		this.elements.type.on('change', this.render);
		this.elements.default_option.on('change', this.render);
		
		this.render();		
	},	
	
	startEdit: function() {			
		this.reset();
		this.currentModel = arguments[0];
		this.setData(this.currentModel);
		this.show();
	},
	
	setData: function(model) {	
		var values = model.toJSON();
		//console.log(values);
		for(name in this.elements) {
			if(name in values) {
				if(name == 'name' && values.new_name) {
					this.elements[name].val(values.new_name);
				} else {
					if(this.elements[name].attr('type') == 'checkbox') {
						if(values[name] == 'on')
							this.elements[name].attr('checked','checked');
						else
							this.elements[name].removeAttr('checked');
					} else {
						this.elements[name].val(values[name]);
					}
				}
			} else {
				if(this.elements[name].attr('type') == 'checkbox') {
					this.elements[name].removeAttr('checked');				
				}
			}
		}
		
		if('vgroups' in values && values.vgroups.length > 0) {			
			this.vgroups = values.vgroups;
			for(var i=0; i<this.vgroups.length; i++) {
				if('rules' in this.vgroups[i]) {}
				else this.vgroups[i].rules = [];					
			}	
			this.currentvgroup = 0;
		} else {
			this.vgroups = [];
		}
		
		this.render();
		this.renderValidationGroups();
	},
	
	addValidationGroup: function() {				
		this.newVGroup = new VGroupTab();
		this.newVGroup.$el.insertBefore('#vGroupTabs li.last');
		this.newVGroup.onAddToHTML();
		this.bindTo(this.newVGroup, 'save.vGroupTab', this.storeNewValidationGroup);
	},
	
	storeNewValidationGroup: function(e) {
		this.newVGroup.dispose();
		this.vgroups.push({name: e.new_name, rules: []});									
		
		this.syncRulesWithViews(this.currentvgroup);		
		this.removeRuleViews(this.currentvgroup);		
		this.currentvgroup = this.vgroups.length-1;
		this.renderValidationGroups();
	},
	
	renameValidationGroup: function(e) {
		for(var i=0; i<this.vgroups.length; i++) {
			if(this.vgroups[i].name == e.old_name) {
				this.vgroups[i].name = e.new_name;
				this.currentvgroup = i;
			}
		}
	},		
	
	renderValidationGroups: function() {			
		for(var i=0; i<this.vgroups.length; i++) {
			if('view' in this.vgroups[i]) {
				this.vgroups[i].view.render();
			} else {
				this.vgroups[i].view = new VGroupTab({name: this.vgroups[i].name, state: this.vgroups[i].state || ''});
				this.vgroups[i].view.$el.insertBefore('#vGroupTabs li.last');
			}
		}		
		
		$('#vGroupTabs li').removeClass('active');
		if(typeof(this.currentvgroup) != 'undefined' && typeof(this.vgroups[this.currentvgroup]) != 'undefined') {
			if(this.vgroups[this.currentvgroup].view)
				this.vgroups[this.currentvgroup].view.$el.addClass('active');
			
			this.renderValidationRules();
		}		
	},
	
	switchVGroup: function(name) {
		for(var i=0; i<this.vgroups.length; i++) {
			if(name == this.vgroups[i].name && this.currentvgroup != i) {										
				this.syncRulesWithViews(this.currentvgroup);		
				this.removeRuleViews(this.currentvgroup);				
				this.currentvgroup = i;	
				this.renderValidationGroups();
				break;
			}
		}
	},
	
	syncRulesWithViews: function(groupIndex) {	
		if(typeof(groupIndex) != 'undefined') {
			var aux = [];
			for(var i=0; i < this.vgroups[groupIndex].rules.length; i++) {
				this.vgroups[groupIndex].rules[i].rule   = this.vgroups[groupIndex].rules[i].view.props.rule;
				this.vgroups[groupIndex].rules[i].state  = this.vgroups[groupIndex].rules[i].view.state;
				this.vgroups[groupIndex].rules[i].order  = this.currentRulesOrder.indexOf(this.vgroups[groupIndex].rules[i].view.$el.data('ruleIndex'));
			}
			//console.log(groupIndex, this.vgroups[groupIndex]);
			this.vgroups[groupIndex].rules.sort(function(a,b) {
				return a.order - b.order;
			});			
		}
	},
	
	removeRuleViews: function(groupIndex) {	
		if(typeof(groupIndex) != 'undefined') {
			for(var i=0; i < this.vgroups[groupIndex].rules.length; i++) {
				if(this.vgroups[groupIndex].rules[i].view) {
					this.vgroups[groupIndex].rules[i].view.dispose();
					delete this.vgroups[groupIndex].rules[i].view;
				}
			}	
		}
	},
	
	addValidationRule: function(e) {
		var rule = e.target.getAttribute('data-rule');		
		this.vgroups[this.currentvgroup].rules.push({rule: rule});		
		this.renderValidationRules();
		e.preventDefault();
	},
		
	renderValidationRules: function() {	
		App.form.currentRulesOrder = [];
		for(var i=0; i<this.vgroups[this.currentvgroup].rules.length; i++) {
			if('view' in this.vgroups[this.currentvgroup].rules[i]) {
				
			} else {
				this.vgroups[this.currentvgroup].rules[i].view = new VRuleView({rule: this.vgroups[this.currentvgroup].rules[i].rule, state: this.vgroups[this.currentvgroup].rules[i].state || ''});
				this.vgroups[this.currentvgroup].rules[i].view.$el.appendTo('#rulePanel').data('ruleIndex', i);
				this.vgroups[this.currentvgroup].rules[i].view.onAddToHTML();
				App.form.currentRulesOrder.push(i);
			}
		}
		
		$( "#rulePanel" ).sortable({
			handle: '.sortHandle', 
			cursor: 'move',
			update: function(e, ui) {
				App.form.currentRulesOrder = [];
				$('#rulePanel').find('li').each(function() {
					App.form.currentRulesOrder.push($(this).data('ruleIndex'));
				});
			}
		});
	},
	
	
	render: function() {					
		var field_name = this.elements.name.val();
		if(field_name.length == 0) {
			this.elements.submit.attr('disabled','disabled');
		} else {
			this.elements.submit.removeAttr('disabled');			
			this.elements.button_field_name.html(field_name);	
		}
                
                
                if ($(this.el).find('select[name=default_option]').val() == 'CUSTOM') {
                    $(this.el).find('input[name=default_value]').show();
                } else {
                    $(this.el).find('input[name=default_value]').hide();
                }
                
		switch(this.elements.type.val()) {
			case 'decimal':
				$(this.el).find('.decimal_digits').show();
				$(this.el).find('.length').hide();
				$(this.el).find('.values').hide();
				break;
			case 'varchar':
			case 'char':
				$(this.el).find('.decimal_digits').hide();
				$(this.el).find('.length').show();
				$(this.el).find('.values').hide();
				break;
			case 'enum':
				$(this.el).find('.decimal_digits').hide();
				$(this.el).find('.length').hide();
				$(this.el).find('.values').show();
				break;
			default: 
				$(this.el).find('.decimal_digits').hide();
				$(this.el).find('.length').hide();
				$(this.el).find('.values').hide();
				break;
		}
	},
	
	reset: function() {	
		//console.log('RESET');
		this.currentModel = null;
		$('#field-form').get(0).reset();
		$('#field-form').find('.button_field_name').html('');
		
		if(typeof(this.vgroups)!='undefined') {
			//console.log(this.vgroups);
			for(var i=0; i<this.vgroups.length; i++) {
				this.removeRuleViews(i);
				this.vgroups[i].view.dispose();
				delete this.vgroups[i].view;
			}
			delete this.vgroups;
		}
		
		this.render();
	},
	
	show: function() {
		$(this.el).show();
	},
	
	hide: function() {
		$(this.el).hide();
	},
	
	submit: function(e) {		
		var aux = $('#field-form').serializeArray();				
		var form_data = {};
		for(i=0; i<aux.length; i++) {
			//console.log(aux[i].name, aux[i].value);
			form_data[aux[i].name] = aux[i].value;		
		}		
		
		if(!this.currentModel) {
			this.currentModel = new Field(form_data);
		} else {				
			if(typeof(this.currentModel.get('name'))!='undefined' && form_data.name != this.currentModel.get('name') && this.currentModel.get('name')!='') {
				form_data.new_name = form_data.name;
				form_data.name = this.currentModel.get('name');
			}
			this.currentModel.set(form_data);
		}
                
                for(k in this.currentModel.attributes) {
                    if (form_data[k] === undefined) {
                        delete this.currentModel.attributes[k];
                    }
                }
                console.log(this.currentModel, form_data)
		
		//add validationGroups & rules
		var vGroups = [];
		for(var i=0; i<this.vgroups.length; i++) {
			if(this.currentvgroup == i) {
				//console.log(i);
				//the other groups should already be synced (auto synced when switching tabs)
				this.syncRulesWithViews(i);
			}
			//if(this.vgroups[i].view.state != 'removed') {
			var vGroup = {name: this.vgroups[i].name, rules: [], state: this.vgroups[i].view.state};			
			for(var j=0; j<this.vgroups[i].rules.length; j++) {
				vGroup.rules.push({rule: this.vgroups[i].rules[j].rule, state: this.vgroups[i].rules[j].state});				
				//vGroup.rules[this.currentRulesOrder.indexOf(this.vgroups[i].rules[j].ruleIndex)] = {rule: this.vgroups[i].rules[j].rule, state: this.vgroups[i].rules[j].state};				
			}			
			vGroups.push(vGroup);
			//}
		}
		
		this.currentModel.set({vgroups: vGroups});
		Fields.add(this.currentModel);
		this.reset();
		this.hide();
		return false;		
	}
	
});

var FieldForm = new FormView();

var AppView = BaseView.extend({

    el: $("#crud"),
	
    events: {
      'click #add_custom'           : 'addCustomField',
	  'click #save_fields'          : 'sendToServer',
	  'keyup #model'             : 'render',
	  'click .quickField'           : 'addQuickField',
	  'click #add_validation_group' : 'addValidationGroup',
    },	
	form: FieldForm,
	initialize: function() {		
		Fields.bind('add', this.addFieldToHTML, this);
		Fields.bind('all', this.render, this);	  
		this.form.hide();
		
		if(presetQuickFields) {
			for(i in presetQuickFields) {
				$('<li><a class="quickField" href="#" data-fieldname="' + i + '">' + i + '</a></li>').appendTo('#quickFieldMenu');
			}
		}
		
		if(presetQuickRules) {
			for(i in presetQuickRules) {
				if(presetQuickRules[i] == 'divider') {
					$('<li class="divider"></li>').appendTo('#quickRulesMenu');
				} else {
					$('<li><a href="#" data-rule="' + i + '">' + presetQuickRules[i] +'</a></li>').appendTo('#quickRulesMenu');
				}
			}
		}
    },
	
    render: function() {
       $('h1').html('Model: ' + $('#model').val());
    },
	
	addQuickField: function(e) {
		var quickFieldData = presetQuickFields[e.target.getAttribute('data-fieldname')];
		var f = new Field(quickFieldData);
		this.form.startEdit(f);
		this.form.submit();
		e.preventDefault();
	},
	
	fieldEdit: function(model) {
		this.form.startEdit(model);
	},	
	
	addValidationGroup: function() {
		this.form.addValidationGroup();
	},
	
	showForm: function() {
		this.form.show();
		this.form.reset();
	},

    addCustomField: function() {		
		var f = new Field();
		this.form.startEdit(f);
	},
	
	addFieldToHTML: function(model) {
		var view = new FieldView({model: model, id: 'field-' + model.id});
		this.$('#field-list').append(view.render().el);
		//this.form.hide();
		this.form.reset();
	},
	
	sendToServer: function() {
		$('#save_errors').hide('fast').find('li').remove();
		$('#save_messages').hide('fast').find('li').remove();
		$('#save_fields').attr('disabled', 'disabled');		
		
		var overwrite_options = {};
		$('input[name*=overwrite]').each(function(){
			var identifier = $(this).attr('name').replace('overwrite[', '').replace(']', '');
			if($(this).prop('checked')) {
				overwrite_options[identifier] = 1;
			} else {
				overwrite_options[identifier] = 0;
			}
		});
		
		var remainingFields = Fields.filter(function(model){ return !model.isRemoved(); }).map(function(model) {return model.toJSON();});
		
		$.post(CRUD_IGNITION_URL + 'save', {model: $('#model').val(), table: $('#table').val(), fields: remainingFields, overwrite: overwrite_options}, function(ajax_result) {
			if(ajax_result.success) {
				window.location.href = CRUD_IGNITION_URL + '?report=Model+created+successfully!';
			} else {
				$('#save_fields').removeAttr('disabled');
				for(i in ajax_result.messages) {
					if(ajax_result.messages[i].replace(' ', '').length>0)
					$('#save_messages ul').append('<li>' + ajax_result.messages[i] + '</li>');				
				}
				for(i in ajax_result.errors) {
					if(ajax_result.errors[i].replace(' ', '').length>0)
					$('#save_errors ul').append('<li>' + ajax_result.errors[i] + '</li>');
				}				
				$('#save_errors').show();
				if($('#save_messages ul li').size()>0)
					$('#save_messages').show();
			}
		}, 'json');
	}
	
  });

	var App = new AppView;
	if(typeof(preload_fields) !== 'undefined') {
		for(var i=0; i<preload_fields.length; i++) {
			Fields.add(preload_fields[i]);;
		}
	}
	
	/* interface popovers */
	$('a[data-popover-placeholder]').each(function() {
		var placeholder = $('#' + this.getAttribute('data-popover-placeholder'));		
		$(this).popover({
			title:   placeholder.find('.title').html(),
			content: placeholder.find('.content').html()
		});
	});
});
		