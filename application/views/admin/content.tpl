{include file="admin/header.tpl"}
<!-- Paste secondary nav here -->
</div> 
<!-- Sidebar ends -->
<!-- Content begins -->
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-newspaper"></span>Content management</span>
        <div class="clear"></div>
    </div>
    
    <!-- Breadcrumbs line -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{$config.admin_url}/dashboard">Dashboard</a></li>
                <li class="current"><a href="{$config.admin_url}/content">Content</a>
                    <ul>
                        <li><a href="ui_icons.html" title="">Icons</a></li>
                        <li><a href="ui_buttons.html" title="">Button sets</a></li>
                        <li><a href="ui_grid.html" title="">Grid</a></li>
                        <li><a href="ui_custom.html" title="">Custom elements</a></li>
                        <li><a href="ui_experimental.html" title="">Experimental</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Main content -->
    <div class="wrapper">
    	
        <div class="widget check">
            <div class="whead"><span class="titleIcon"><input type="checkbox" id="titleCheck" name="titleCheck" /></span><h6>Deals</h6><div class="clear"></div></div>
            <table cellpadding="0" cellspacing="0" width="100%" class="tDefault checkAll tMedia" id="checkAll">
                <thead>
                    <tr>
                        <td><img src="{$config.assets.img_url}/aquincum/images/elements/other/tableArrows.png" alt="" /></td>
                        <td width="50">Image</td>
                        <td class="sortCol"><div>Deal details<span></span></div></td>
                        <td width="130" class="sortCol"><div>Date added<span></span></div></td>
                        <td width="130" class="sortCol"><div>Date last updated<span></span></div></td>
                        <td width="120">Account</td>
                        <td width="100">Actions</td>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="6">
                            <div class="itemActions">
                                <label>Apply action:</label>
                                <select>
                                    <option value="">Select action...</option>
                                    <option value="Edit">Edit</option>
                                    <option value="Delete">Delete</option>
                                    <option value="Move">Move somewhere</option>
                                </select>
                            </div>
                            <div class="tPages">
                                <ul class="pages">
                                    <li class="prev"><a href="#" title=""><span class="icon-arrow-14"></span></a></li>
                                    <li><a href="#" title="" class="active">1</a></li>
                                    <li><a href="#" title="">2</a></li>
                                    <li><a href="#" title="">3</a></li>
                                    <li><a href="#" title="">4</a></li>
                                    <li>...</li>
                                    <li><a href="#" title="">20</a></li>
                                    <li class="next"><a href="#" title=""><span class="icon-arrow-17"></span></a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
					{foreach from=$deals item=deal}
                    <tr>
                        <td><input type="checkbox" name="checkRow" /></td>
                        <td><a href="{$deal.photos.main.url}" title="" class="lightbox"><img src="{$deal.photos.main.thumbs.small}" alt="" /></a></td>
                        <td class="textL">
							<h6 class="pt20">{$deal.title}</h6>
							<p><a href="#" title="">{$deal.destination.full_label}</a></p>
						</td>
                        <td>{$deal.date_created|kms_lang_dateformat}</td>
                        <td>{$deal.date_created|kms_lang_dateformat}</td>
                        <td class="fileInfo"><span><strong>Size:</strong> 215 Kb</span><span><strong>Format:</strong> .jpg</span></td>
                        <td class="tableActs">
                            <a href="{$config.admin_url}/deals/edit/{$deal.id}" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
                            <a href="#" class="tablectrl_small bDefault tipS" title="Remove" data-delete="{$deal.id}"><span class="iconb" data-icon="&#xe136;"></span></a>
                            <a href="#" class="tablectrl_small bDefault tipS" title="Options"><span class="iconb" data-icon="&#xe1f7;"></span></a>
                        </td>
                    </tr>
					{/foreach}
                </tbody>
            </table>
        </div>
        
        <div class="divider"><span></span></div>
        
