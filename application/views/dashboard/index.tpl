{include file='web/includes/barebones-header.tpl'}
{include file='web/includes/header.tpl'}
<div class="trasee-tabs container">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#trasee" aria-controls="home" role="tab" data-toggle="tab">Trasee</a></li>
        <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="trasee">
            <h2>Add</h2>
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="nume" placeholder="Nume" value="" class="form-control" />
                </div>
                <div class="col-md-2">
                    <input type="file" name="file" value=""/>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-default" href="javascript:;">Add</a>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nume</th>
                        <th>Data</th>
                        <th>Lungime</th>
                        <th>Durata</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Bulibasa</td>
                        <td>12.12.2015</td>
                        <td>20cm</td>
                        <td>Mult</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Bulibasa</td>
                        <td>12.12.2015</td>
                        <td>20cm</td>
                        <td>Mult</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Bulibasa</td>
                        <td>12.12.2015</td>
                        <td>20cm</td>
                        <td>Mult</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div role="tabpanel" class="tab-pane" id="profile">

        </div>
    </div>
</div>