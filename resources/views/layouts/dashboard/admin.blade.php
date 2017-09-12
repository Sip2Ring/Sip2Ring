<div ng-controller="dashboardController">
    <div class="panel-container col-sm-12">
        <div class="panels panels-default collapsible">
            <div class="panel_header">
                <span class="fa fa-bolt panel-icon"></span>
                Demo Chart
                <div class="pull-right iconBar">
                    <span class="minimise cntrlBtnGrp">
                        <span class="fa fa-minus"></span>
                    </span>
                    <span class="closeme cntrlBtnGrp">
                        <span class="fa fa-close"></span>
                    </span>
                </div>
            </div>
            <div class="panel_warper">
                <div class="panel_body">
                    <div id="container" style="width: 100% !important; height: 500px; min-width: 100% !important;"></div>
                    <hr />
                </div>                     
            </div>
        </div>
    </div>
</div>
<div ng-controller="tableController">
    <div class="panel-container col-sm-12">
        <div class="panels panels-default collapsible">
            <div class="panel_header">
                <span class="fa fa-bolt panel-icon"></span>
                Demo Table(Fetching from DB)
                <div class="pull-right iconBar">
                    <span class="minimise cntrlBtnGrp">
                        <span class="fa fa-minus"></span>
                    </span>
                    <span class="closeme cntrlBtnGrp">
                        <span class="fa fa-close"></span>
                    </span>
                    <div class="setting cntrlBtnGrp">
                        <i class="fa fa-cog fa-lg"></i>
                        <ul class="settingPanels">
                            <li ng-repeat="(key, value) in usersTableKey">
                                <input type="checkbox" name="rowShort" class="rowShort" checked id="<%key%>">
                                <div><%key%></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="panel_loader loader"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
            <div class="panel_warper">
                <div class="panel_body">
                    <table class="table table-hover table-stripped no-border">
                        <thead>
                            <tr>
                                <th ng-repeat="(key, value) in usersTableKey" class="<%key%>"><%key%></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="data in usersTableData">
                                <td class="id"><%data.id%></td>
                                <td class="name"><%data.role_name%></td>
                                <td class="created_at"><%data.created_at%></td>
                                <td class="updated_at"><%data.updated_at%></td>
                            </tr>
                        </tbody>
                    </table>
                </div>                     
            </div>
        </div>
    </div>

</div>