<div class="modal-header">
    <button type="button" class="close" data-ng-click="cancel()" aria-hidden="true">&times;</button>
    <h3 class="modal-title" id="modal-title">
        <span class="icon"><i class="fa fa-address-card-o"></i></span>    
        <span>Contact Agent</span>
    </h3>
</div>
<div class="modal-body">

    <form id="contact-form" method="post" class="form" role="form">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-user"></i></span>
            <input type="text" class="form-control" placeholder="Your Name" ng-model="name" />
        </div>
        <br />
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
            <input type="text" class="form-control" placeholder="Your Phone Number" ng-model="phone" />
        </div>
        <br />
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
            <input type="text" class="form-control" placeholder="Your Email Address" ng-model="email" />
        </div>
        
        <br />
        <label for="message"><i class="fa fa-sticky-note-o"></i> Message</label>
        <textarea class="form-control" id="message" name="message" placeholder="Message" rows="5" ng-model="message"></textarea>
        <br>
    </form>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" ng-click="send()">Send</button>
</div>
