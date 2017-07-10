<div class="modal-header">
    <button type="button" class="close" data-ng-click="cancel()" aria-hidden="true">&times;</button>
    <h3 class="modal-title" id="modal-title">
        <span class="icon"><i class="fa fa-envelope-o"></i></span>    
        <span>Contact Agent</span>
    </h3>
</div>
<div class="modal-body">
    <h3 ng-bind="message"></h3>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" ng-click="cancel()">OK</button>
</div>
