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
		} else {
			this.props.rule = '';
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
	markForRemoval: function() {
		this.state = 'removed';
		this.$el.find('.remove').hide();
		this.$el.find('.restore').show();
		this.$el.find('input').css('text-decoration', 'line-through');
	},
	restore: function() {
		this.state = '';
		this.$el.find('.remove').show();
		this.$el.find('.restore').hide();
		this.$el.find('input').css('text-decoration', 'none');
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
	
	markForRemoval: function() {
		//console.log('removed');
		this.state = 'removed';
		this.$el.find('.remove').hide();
		this.$el.find('.restore').show();
		this.$el.find('.name').css('text-decoration', 'line-through');
	},
	restore: function() {
		this.state = '';
		this.$el.find('.remove').show();
		this.$el.find('.restore').hide();
		this.$el.find('.name').css('text-decoration', 'none');
	},
	onAddToHTML: function() {
		this.$el.find('input').focus();
	}
});	



var Field = Backbone.Model.extend({	
	idAttribute: 'name',
	initialize: function() {		
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
	url: 'http://www.cautavacante.ro/ajax',	
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
		
		for(name in this.elements) {
			if(name in values) {
				if(name == 'name' && values.new_name) {
					this.elements[name].val(values.new_name);
				} else {
					this.elements[name].val(values[name]);
				}
			}
		}
		
		if('vgroups' in values && values.vgroups.length > 0) {			
			this.vgroups = values.vgroups;
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
				this.vgroups[i].view = new VGroupTab({name: this.vgroups[i].name});
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
			for(var i=0; i < this.vgroups[groupIndex].rules.length; i++) {
				this.vgroups[groupIndex].rules[i].rule = this.vgroups[groupIndex].rules[i].view.props.rule;
				this.vgroups[groupIndex].rules[i].state = this.vgroups[groupIndex].rules[i].view.state;
			}
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
	},
		
	renderValidationRules: function() {	
		for(var i=0; i<this.vgroups[this.currentvgroup].rules.length; i++) {
			if('view' in this.vgroups[this.currentvgroup].rules[i]) {
				
			} else {
				this.vgroups[this.currentvgroup].rules[i].view = new VRuleView({rule: this.vgroups[this.currentvgroup].rules[i].rule});
				this.vgroups[this.currentvgroup].rules[i].view.$el.appendTo('#rulePanel');
				this.vgroups[this.currentvgroup].rules[i].view.onAddToHTML();
			}
		}
		
		$( "#rulePanel" ).sortable({handle: '.sortHandle', cursor: 'move'});
	},
	
	
	render: function() {					
		var field_name = this.elements.name.val();
		if(field_name.length == 0) {
			this.elements.submit.attr('disabled','disabled');
		} else {
			this.elements.submit.removeAttr('disabled');			
			this.elements.button_field_name.html(field_name);	
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
		console.log('RESET');
		this.currentModel = null;
		$('#field-form').get(0).reset();
		$('#field-form').find('.button_field_name').html('');
		
		if(typeof(this.vgroups)!='undefined') {
			console.log(this.vgroups);
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
			form_data[aux[i].name] = aux[i].value;				
		}		
		
		if(!this.currentModel) {
			this.currentModel = new Field(form_data);
		} else {				
			if(form_data.name != this.currentModel.get('name')) {
				form_data.new_name = form_data.name;
				form_data.name = this.currentModel.get('name');
			}
			this.currentModel.set(form_data);
		}
		
		console.log(form_data);
		
		//add validationGroups & rules
		var vGroups = [];
		for(var i=0; i<this.vgroups.length; i++) {
			this.syncRulesWithViews(i);
			if(this.vgroups[i].view.state != 'removed') {
				var vGroup = {name: this.vgroups[i].name, rules: []};			
				for(var j=0; j<this.vgroups[i].rules.length; j++) {
					if(this.vgroups[i].rules[j].state != 'removed')
						vGroup.rules.push({rule: this.vgroups[i].rules[j].rule});
				}			
				vGroups.push(vGroup);
			}
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
	  'keypress #model'             : 'render',
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
		var remainingFields = Fields.filter(function(model){ return model.get('state') != 'removed'; }).map(function(model) {return model.toJSON();});
		$.post('./save', {model: $('#model').val(), table: $('#table').val(), fields: Fields.toJSON()}, function() {
			console.log('saved!');
		});
	}
	
  });

	var App = new AppView;
	for(var i=0; i<preload_fields.length; i++) {
		Fields.add(preload_fields[i]);;
	}

});
		