{include file="admin/header.tpl"}
   
<!-- Content begins -->
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-screen"></span>Dashboard</span>
        <ul class="quickStats">
            <li>
                <a href="" class="blueImg"><img src="{$config.assets.img_url}/aquincum/images/icons/quickstats/plus.png" alt="" /></a>
                <div class="floatR"><strong class="blue">5489</strong><span>visits</span></div>
            </li>
            <li>
                <a href="" class="redImg"><img src="{$config.assets.img_url}/aquincum/images/icons/quickstats/user.png" alt="" /></a>
                <div class="floatR"><strong class="blue">4658</strong><span>users</span></div>
            </li>
            <li>
                <a href="" class="greenImg"><img src="{$config.assets.img_url}/aquincum/images/icons/quickstats/money.png" alt="" /></a>
                <div class="floatR"><strong class="blue">1289</strong><span>orders</span></div>
            </li>
        </ul>
        <div class="clear"></div>
    </div>
    
    <!-- Breadcrumbs line -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">UI elements</a>
                    <ul>
                        <li><a href="ui.html" title="">General elements</a></li>
                        <li><a href="ui_icons.html" title="">Icons</a></li>
                         <li><a href="ui_buttons.html" title="">Button sets</a></li>
                        <li><a href="ui_custom.html" title="">Custom elements</a></li>
                        <li><a href="ui_experimental.html" title="">Experimental</a></li>
                    </ul>
                </li>
                <li class="current"><a href="ui_grid.html" title="">Grid</a></li>
            </ul>
        </div>
        
        <div class="breadLinks">
            <ul>
                <li><a href="#" title=""><i class="icos-list"></i><span>Orders</span> <strong>(+58)</strong></a></li>
                <li><a href="#" title=""><i class="icos-check"></i><span>Tasks</span> <strong>(+12)</strong></a></li>
                <li class="has">
                    <a title="">
                        <i class="icos-money3"></i>
                        <span>Invoices</span>
                        <span><img src="{$config.assets.img_url}/aquincum/images/elements/control/hasddArrow.png" alt="" /></span>
                    </a>
                    <ul>
                        <li><a href="#" title=""><span class="icos-add"></span>New invoice</a></li>
                        <li><a href="#" title=""><span class="icos-archive"></span>History</a></li>
                        <li><a href="#" title=""><span class="icos-printer"></span>Print invoices</a></li>
                    </ul>
                </li>
            </ul>
             <div class="clear"></div>
        </div>
    </div>
    
    <!-- Main content -->
    <div class="wrapper">
        <ul class="middleNavR">
            <li><a href="#" title="Add an article" class="tipN"><img src="{$config.assets.img_url}/aquincum/images/icons/middlenav/create.png" alt="" /></a></li>
            <li><a href="#" title="Upload files" class="tipN"><img src="{$config.assets.img_url}/aquincum/images/icons/middlenav/upload.png" alt="" /></a></li>
            <li><a href="#" title="Add something" class="tipN"><img src="{$config.assets.img_url}/aquincum/images/icons/middlenav/add.png" alt="" /></a></li>
            <li><a href="#" title="Messages" class="tipN"><img src="{$config.assets.img_url}/aquincum/images/icons/middlenav/dialogs.png" alt="" /></a><strong>8</strong></li>
            <li><a href="#" title="Check statistics" class="tipN"><img src="{$config.assets.img_url}/aquincum/images/icons/middlenav/stats.png" alt="" /></a></li>
        </ul>
    
    	<!-- Chart -->
        <div class="widget chartWrapper">
            <div class="whead"><h6>Charts</h6>
                <div class="titleOpt">
                    <a href="#" data-toggle="dropdown"><span class="icos-cog3"></span><span class="clear"></span></a>
                    <ul class="dropdown-menu pull-right">
                            <li><a href="#"><span class="icos-add"></span>Add</a></li>
                            <li><a href="#"><span class="icos-trash"></span>Remove</a></li>
                            <li><a href="#" class=""><span class="icos-pencil"></span>Edit</a></li>
                            <li><a href="#" class=""><span class="icos-heart"></span>Do whatever you like</a></li>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <div class="body"><div class="chart"></div></div>
        </div>
    	
    
    	<!-- 6 + 6 -->
        <div class="fluid">
        
            <!-- Messages #1 -->
            <div class="widget grid6">
                <div class="whead">
                    <h6>Messages layout #1</h6>
                    <div class="on_off">
                        <span class="icon-reload-CW"></span>
                        <input type="checkbox" id="check1" checked="checked" name="chbox" />
                    </div>            
                    <div class="clear"></div>
                </div>
                
                <ul class="messagesOne">
                    <li class="by_user">
                        <a href="#" title=""><img src="{$config.assets.img_url}/aquincum/images/live/face1.png" alt="" /></a>
                        <div class="messageArea">
                            <span class="aro"></span>
                            <div class="infoRow">
                                <span class="name"><strong>John</strong> says:</span>
                                <span class="time">3 hours ago</span>
                                <div class="clear"></div>
                            </div>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam vel est enim, vel eleifend felis. Ut volutpat, leo eget euismod scelerisque, eros purus lacinia velit, nec rhoncus mi dui eleifend orci. 
                            Phasellus ut sem urna, id congue libero. Nulla eget arcu vel massa suscipit ultricies ac id velit
                        </div>
                        <div class="clear"></div>
                    </li>
                
                    <li class="divider"><span></span></li>
                
                    <li class="by_me">
                        <a href="#" title=""><img src="{$config.assets.img_url}/aquincum/images/live/face2.png" alt="" /></a>
                        <div class="messageArea">
                            <span class="aro"></span>
                            <div class="infoRow">
                                <span class="name"><strong>Eugene</strong> says:</span>
                                <span class="time">3 hours ago</span>
                                <div class="clear"></div>
                            </div>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam vel est enim, vel eleifend felis. Ut volutpat, leo eget euismod scelerisque, eros purus lacinia velit, nec rhoncus mi dui eleifend orci. 
                            Phasellus ut sem urna, id congue libero. Nulla eget arcu vel massa suscipit ultricies ac id velit
                        </div>
                        <div class="clear"></div>
                    </li>
                
                    <li class="by_me">
                        <a href="#" title=""><img src="{$config.assets.img_url}/aquincum/images/live/face2.png" alt="" /></a>
                        <div class="messageArea">
                            <span class="aro"></span>
                            <div class="infoRow">
                                <span class="name"><strong>Eugene</strong> says:</span>
                                <span class="time">3 hours ago</span>
                                <div class="clear"></div>
                            </div>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam vel est enim, vel eleifend felis. Ut volutpat, leo eget euismod scelerisque, eros purus lacinia velit, nec rhoncus mi dui eleifend orci. 
                            Phasellus ut sem urna, id congue libero. Nulla eget arcu vel massa suscipit ultricies ac id velit
                        </div>
                        <div class="clear"></div>
                    </li>
                    
                    <li class="divider"><span></span></li>
                
                    <li class="by_user">
                        <a href="#" title=""><img src="{$config.assets.img_url}/aquincum/images/live/face1.png" alt="" /></a>
                        <div class="messageArea">
                            <span class="aro"></span>
                            <div class="infoRow">
                                <span class="name"><strong>John</strong> says:</span>
                                <span class="time">3 hours ago</span>
                                <div class="clear"></div>
                            </div>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam vel est enim, vel eleifend felis. Ut volutpat, leo eget euismod scelerisque, eros purus lacinia velit, nec rhoncus mi dui eleifend orci. 
                            Phasellus ut sem urna, id congue libero. Nulla eget arcu vel massa suscipit ultricies ac id velit
                        </div>
                        <div class="clear"></div>
                    </li>
                    
                    <li class="divider"><span></span></li>
                
                    <li class="by_me">
                        <a href="#" title=""><img src="{$config.assets.img_url}/aquincum/images/live/face2.png" alt="" /></a>
                        <div class="messageArea">
                            <span class="aro"></span>
                            <div class="infoRow">
                                <span class="name"><strong>Eugene</strong> says:</span>
                                <span class="time">3 hours ago</span>
                                <div class="clear"></div>
                            </div>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam vel est enim, vel eleifend felis. Ut volutpat, leo eget euismod scelerisque, eros purus lacinia velit, nec rhoncus mi dui eleifend orci. 
                            Phasellus ut sem urna, id congue libero. Nulla eget arcu vel massa suscipit ultricies ac id velit
                        </div>
                        <div class="clear"></div>
                    </li>
                </ul>
            </div>
            
            <!-- Calendar -->
            <div class="widget grid6">
                <div class="whead"><h6>Calendar</h6><div class="clear"></div></div>
                <div id="calendar"></div>
            </div>
            <div class="clear"></div>
        </div>

        <div class="widget">
        <div class="whead"><h6>Table with hidden toolbar</h6><div class="clear"></div></div>
        <div id="dyn" class="hiddenpars">
            <a class="tOptions" title="Options"><img src="{$config.assets.img_url}/aquincum/images/icons/options" alt="" /></a>
            <table cellpadding="0" cellspacing="0" border="0" class="dTable" id="dynamic">
            <thead>
            <tr>
            <th>Rendering engine<span class="sorting" style="display: block;"></span></th>
            <th>Browser</th>
            <th>Platform(s)</th>
            <th>Engine version</th>
            </tr>
            </thead>
            <tbody>
            <tr class="gradeX">
            <td>Trident</td>
            <td>Internet
            Explorer 4.0</td>
            <td>Win 95+</td>
            <td class="center">4</td>
            </tr>
            <tr class="gradeC">
            <td>Trident</td>
            <td>Internet
            Explorer 5.0</td>
            <td>Win 95+</td>
            <td class="center">5</td>
            </tr>
            <tr class="gradeA">
            <td>Trident</td>
            <td>Internet
            Explorer 5.5</td>
            <td>Win 95+</td>
            <td class="center">5.5</td>
            </tr>
            <tr class="gradeA">
            <td>Trident</td>
            <td>Internet
            Explorer 6</td>
            <td>Win 98+</td>
            <td class="center">6</td>
            </tr>
            <tr class="gradeA">
            <td>Trident</td>
            <td>Internet Explorer 7</td>
            <td>Win XP SP2+</td>
            <td class="center">7</td>
            </tr>
            <tr class="gradeA">
            <td>Trident</td>
            <td>AOL browser (AOL desktop)</td>
            <td>Win XP</td>
            <td class="center">6</td>
            </tr>
            <tr class="gradeA">
            <td>Gecko</td>
            <td>Firefox 1.0</td>
            <td>Win 98+ / OSX.2+</td>
            <td class="center">1.7</td>
            </tr>
            <tr class="gradeA">
            <td>Gecko</td>
            <td>Firefox 1.5</td>
            <td>Win 98+ / OSX.2+</td>
            <td class="center">1.8</td>
            </tr>
            <tr class="gradeA">
            <td>Gecko</td>
            <td>Firefox 2.0</td>
            <td>Win 98+ / OSX.2+</td>
            <td class="center">1.8</td>
            </tr>
            <tr class="gradeA">
            <td>Gecko</td>
            <td>Firefox 3.0</td>
            <td>Win 2k+ / OSX.3+</td>
            <td class="center">1.9</td>
            </tr>
            <tr class="gradeA">
            <td>Gecko</td>
            <td>Camino 1.0</td>
            <td>OSX.2+</td>
            <td class="center">1.8</td>
            </tr>
            <tr class="gradeA">
            <td>Gecko</td>
            <td>Camino 1.5</td>
            <td>OSX.3+</td>
            <td class="center">1.8</td>
            </tr>
            <tr class="gradeA">
            <td>Gecko</td>
            <td>Netscape 7.2</td>
            <td>Win 95+ / Mac OS 8.6-9.2</td>
            <td class="center">1.7</td>
            </tr>
            <tr class="gradeA">
            <td>Gecko</td>
            <td>Netscape Browser 8</td>
            <td>Win 98SE+</td>
            <td class="center">1.7</td>
            </tr>
            <tr class="gradeA">
            <td>Gecko</td>
            <td>Netscape Navigator 9</td>
            <td>Win 98+ / OSX.2+</td>
            <td class="center">1.8</td>
            </tr>
            <tr class="gradeA">
            <td>Gecko</td>
            <td>Mozilla 1.0</td>
            <td>Win 95+ / OSX.1+</td>
            <td class="center">1</td>
            </tr>
            <tr class="gradeA">
            <td>Gecko</td>
            <td>Mozilla 1.1</td>
            <td>Win 95+ / OSX.1+</td>
            <td class="center">1.1</td>
            </tr>
            <tr class="gradeA">
            <td>Gecko</td>
            <td>Mozilla 1.2</td>
            <td>Win 95+ / OSX.1+</td>
            <td class="center">1.2</td>
            </tr>
            <tr class="gradeA">
            <td>Gecko</td>
            <td>Mozilla 1.3</td>
            <td>Win 95+ / OSX.1+</td>
            <td class="center">1.3</td>
            </tr>
            <tr class="gradeA">
            <td>Gecko</td>
            <td>Mozilla 1.4</td>
            <td>Win 95+ / OSX.1+</td>
            <td class="center">1.4</td>
            </tr>
            <tr class="gradeA">
            <td>Gecko</td>
            <td>Mozilla 1.5</td>
            <td>Win 95+ / OSX.1+</td>
            <td class="center">1.5</td>
            </tr>
            <tr class="gradeA">
            <td>Gecko</td>
            <td>Mozilla 1.6</td>
            <td>Win 95+ / OSX.1+</td>
            <td class="center">1.6</td>
            </tr>
            <tr class="gradeA">
            <td>Gecko</td>
            <td>Mozilla 1.7</td>
            <td>Win 98+ / OSX.1+</td>
            <td class="center">1.7</td>
            </tr>
            <tr class="gradeA">
            <td>Gecko</td>
            <td>Mozilla 1.8</td>
            <td>Win 98+ / OSX.1+</td>
            <td class="center">1.8</td>
            </tr>
            <tr class="gradeA">
            <td>Gecko</td>
            <td>Seamonkey 1.1</td>
            <td>Win 98+ / OSX.2+</td>
            <td class="center">1.8</td>
            </tr>
            <tr class="gradeA">
            <td>Gecko</td>
            <td>Epiphany 2.20</td>
            <td>Gnome</td>
            <td class="center">1.8</td>
            </tr>
            <tr class="gradeA">
            <td>Webkit</td>
            <td>Safari 1.2</td>
            <td>OSX.3</td>
            <td class="center">125.5</td>
            </tr>
            <tr class="gradeA">
            <td>Webkit</td>
            <td>Safari 1.3</td>
            <td>OSX.3</td>
            <td class="center">312.8</td>
            </tr>
            <tr class="gradeA">
            <td>Webkit</td>
            <td>Safari 2.0</td>
            <td>OSX.4+</td>
            <td class="center">419.3</td>
            </tr>
            <tr class="gradeA">
            <td>Webkit</td>
            <td>Safari 3.0</td>
            <td>OSX.4+</td>
            <td class="center">522.1</td>
            </tr>
            <tr class="gradeA">
            <td>Webkit</td>
            <td>OmniWeb 5.5</td>
            <td>OSX.4+</td>
            <td class="center">420</td>
            </tr>
            <tr class="gradeA">
            <td>Webkit</td>
            <td>iPod Touch / iPhone</td>
            <td>iPod</td>
            <td class="center">420.1</td>
            </tr>
            <tr class="gradeA">
            <td>Webkit</td>
            <td>S60</td>
            <td>S60</td>
            <td class="center">413</td>
            </tr>
            <tr class="gradeA">
            <td>Presto</td>
            <td>Opera 7.0</td>
            <td>Win 95+ / OSX.1+</td>
            <td class="center">-</td>
            </tr>
            <tr class="gradeA">
            <td>Presto</td>
            <td>Opera 7.5</td>
            <td>Win 95+ / OSX.2+</td>
            <td class="center">-</td>
            </tr>
            <tr class="gradeA">
            <td>Presto</td>
            <td>Opera 8.0</td>
            <td>Win 95+ / OSX.2+</td>
            <td class="center">-</td>
            </tr>
            <tr class="gradeA">
            <td>Presto</td>
            <td>Opera 8.5</td>
            <td>Win 95+ / OSX.2+</td>
            <td class="center">-</td>
            </tr>
            <tr class="gradeA">
            <td>Presto</td>
            <td>Opera 9.0</td>
            <td>Win 95+ / OSX.3+</td>
            <td class="center">-</td>
            </tr>
            <tr class="gradeA">
            <td>Presto</td>
            <td>Opera 9.2</td>
            <td>Win 88+ / OSX.3+</td>
            <td class="center">-</td>
            </tr>
            <tr class="gradeA">
            <td>Presto</td>
            <td>Opera 9.5</td>
            <td>Win 88+ / OSX.3+</td>
            <td class="center">-</td>
            </tr>
            <tr class="gradeA">
            <td>Presto</td>
            <td>Opera for Wii</td>
            <td>Wii</td>
            <td class="center">-</td>
            </tr>
            <tr class="gradeA">
            <td>Presto</td>
            <td>Nokia N800</td>
            <td>N800</td>
            <td class="center">-</td>
            </tr>
            <tr class="gradeA">
            <td>Presto</td>
            <td>Nintendo DS browser</td>
            <td>Nintendo DS</td>
            <td class="center">8.5</td>
            </tr>
            <tr class="gradeC">
            <td>KHTML</td>
            <td>Konqureror 3.1</td>
            <td>KDE 3.1</td>
            <td class="center">3.1</td>
            </tr>
            <tr class="gradeA">
            <td>KHTML</td>
            <td>Konqureror 3.3</td>
            <td>KDE 3.3</td>
            <td class="center">3.3</td>
            </tr>
            <tr class="gradeA">
            <td>KHTML</td>
            <td>Konqureror 3.5</td>
            <td>KDE 3.5</td>
            <td class="center">3.5</td>
            </tr>
            <tr class="gradeX">
            <td>Tasman</td>
            <td>Internet Explorer 4.5</td>
            <td>Mac OS 8-9</td>
            <td class="center">-</td>
            </tr>
            <tr class="gradeC">
            <td>Tasman</td>
            <td>Internet Explorer 5.1</td>
            <td>Mac OS 7.6-9</td>
            <td class="center">1</td>
            </tr>
            <tr class="gradeC">
            <td>Tasman</td>
            <td>Internet Explorer 5.2</td>
            <td>Mac OS 8-X</td>
            <td class="center">1</td>
            </tr>
            <tr class="gradeA">
            <td>Misc</td>
            <td>NetFront 3.1</td>
            <td>Embedded devices</td>
            <td class="center">-</td>
            </tr>
            <tr class="gradeA">
            <td>Misc</td>
            <td>NetFront 3.4</td>
            <td>Embedded devices</td>
            <td class="center">-</td>
            </tr>
            <tr class="gradeX">
            <td>Misc</td>
            <td>Dillo 0.8</td>
            <td>Embedded devices</td>
            <td class="center">-</td>
            </tr>
            <tr class="gradeX">
            <td>Misc</td>
            <td>Links</td>
            <td>Text only</td>
            <td class="center">-</td>
            </tr>
            <tr class="gradeX">
            <td>Misc</td>
            <td>Lynx</td>
            <td>Text only</td>
            <td class="center">-</td>
            </tr>
            <tr class="gradeC">
            <td>Misc</td>
            <td>IE Mobile</td>
            <td>Windows Mobile 6</td>
            <td class="center">-</td>
            </tr>
            <tr class="gradeC">
            <td>Misc</td>
            <td>PSP browser</td>
            <td>PSP</td>
            <td class="center">-</td>
            </tr>
            <tr class="gradeU">
            <td>Other browsers</td>
            <td>All others</td>
            <td>-</td>
            <td class="center">-</td>
            </tr>
            </tbody>
            </table> 
        </div>
        </div>    
        
        <div class="fluid">
        	
            <div class="grid6">
                <!-- Search widget -->
                <div class="searchLine">
                    <form action="">
                        <input type="text" name="search" class="ac" placeholder="Enter search text..." />
                       <button type="submit" name="find" value=""><span class="icos-search"></span></button>
                    </form>
                </div>
                
                <!-- Multiple files uploader -->
                <div class="widget">    
                    <div class="whead"><h6>WYSIWYG editor</h6><div class="clear"></div></div>
                    <textarea id="editor" name="editor" rows="" cols="16">Some cool stuff here</textarea>                    
                </div>
            </div>
            
            <!-- Media table -->
          <div class="widget check grid6">
            <div class="whead">
                <span class="titleIcon"><input type="checkbox" id="titleCheck" name="titleCheck" /></span>
                <h6>Media table</h6><div class="clear"></div>
            </div>
            <table cellpadding="0" cellspacing="0" width="100%" class="tDefault checkAll tMedia" id="checkAll">
                <thead>
                    <tr>
                        <td><img src="{$config.assets.img_url}/aquincum/images/elements/other/tableArrows.png" alt="" /></td>
                        <td width="50">Image</td>
                        <td class="sortCol"><div>Description<span></span></div></td>
                        <td width="130" class="sortCol"><div>Date<span></span></div></td>
                        <td width="120">File info</td>
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
                    <tr>
                        <td><input type="checkbox" name="checkRow" /></td>
                        <td><a href="images/big.png" title="" class="lightbox"><img src="{$config.assets.img_url}/aquincum/images/live/face3.png" alt="" /></a></td>
                        <td class="textL"><a href="#" title="">Image1 description</a></td>
                        <td>Feb 12, 2012. 12:28</td>
                        <td class="fileInfo"><span><strong>Size:</strong> 215 Kb</span><span><strong>Format:</strong> .jpg</span></td>
                        <td class="tableActs">
                            <a href="#" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
                            <a href="#" class="tablectrl_small bDefault tipS" title="Remove"><span class="iconb" data-icon="&#xe136;"></span></a>
                            <a href="#" class="tablectrl_small bDefault tipS" title="Options"><span class="iconb" data-icon="&#xe1f7;"></span></a>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="checkRow" /></td>
                        <td><a href="images/big.png" title="" class="lightbox"><img src="{$config.assets.img_url}/aquincum/images/live/face7.png" alt="" /></a></td>
                        <td class="textL"><a href="#" title="">Image1 description</a></td>
                        <td>Feb 12, 2012. 12:28</td>
                        <td class="fileInfo"><span><strong>Size:</strong> 215 Kb</span><span><strong>Format:</strong> .jpg</span></td>
                        <td class="tableActs">
                            <a href="#" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
                            <a href="#" class="tablectrl_small bDefault tipS" title="Remove"><span class="iconb" data-icon="&#xe136;"></span></a>
                            <a href="#" class="tablectrl_small bDefault tipS" title="Options"><span class="iconb" data-icon="&#xe1f7;"></span></a>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="checkRow" /></td>
                        <td><a href="images/big.png" title="" class="lightbox"><img src="{$config.assets.img_url}/aquincum/images/live/face6.png" alt="" /></a></td>
                        <td class="textL"><a href="#" title="">Image1 description</a></td>
                        <td>Feb 12, 2012. 12:28</td>
                        <td class="fileInfo"><span><strong>Size:</strong> 215 Kb</span><span><strong>Format:</strong> .jpg</span></td>
                        <td class="tableActs">
                            <a href="#" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
                            <a href="#" class="tablectrl_small bDefault tipS" title="Remove"><span class="iconb" data-icon="&#xe136;"></span></a>
                            <a href="#" class="tablectrl_small bDefault tipS" title="Options"><span class="iconb" data-icon="&#xe1f7;"></span></a>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="checkRow" /></td>
                        <td><a href="images/big.png" title="" class="lightbox"><img src="{$config.assets.img_url}/aquincum/images/live/face5.png" alt="" /></a></td>
                        <td class="textL"><a href="#" title="">Image1 description</a></td>
                        <td>Feb 12, 2012. 12:28</td>
                        <td class="fileInfo"><span><strong>Size:</strong> 215 Kb</span><span><strong>Format:</strong> .jpg</span></td>
                        <td class="tableActs">
                            <a href="#" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
                            <a href="#" class="tablectrl_small bDefault tipS" title="Remove"><span class="iconb" data-icon="&#xe136;"></span></a>
                            <a href="#" class="tablectrl_small bDefault tipS" title="Options"><span class="iconb" data-icon="&#xe1f7;"></span></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
            
        </div>
        
        
        
    </div>
    <!-- Main content ends -->
    
</div>
<!-- Content ends -->

</body>
</html>
