<div id="emailUser" class="modal fade " role="dialog">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">

        <h4>
          <span class="text2">Send Email</span>
        </h4>
        <a class="close" data-dismiss="modal">×</a>
      </div>
      <form>
        <input type="hidden" class="userId" >

        <div class="modal-body">  

          <div class="alert alert-success alert-dismissible fade show successMsg" style="display:none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
          <div class="alert alert-danger alert-dismissible fade show errorMsg" style="display:none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-sm-3">
                <label>Email</label>
              </div>
              <div class="col-sm-9">
                <input type="text" name="user_email" class="form-control userEmail"  placeholder="User Email" disabled>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-sm-3">
                <label>Subject</label>
              </div>
              <div class="col-sm-9">
                <input type="text" name="user_subject" class="form-control emailSubject"  placeholder="Subject" >
                <span class="error errorStyle"><p id="emailSubject_error"></p></span>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-sm-3">
                <label>Message</label>
              </div>
              <div class="col-sm-9">
                <textarea class="form-control emailMsg" placeholder="Type your message here ..." rows="10" name="editor1"></textarea>
                <span class="error errorStyle"><p id="emailMsg_error"></p></span>
              </div>
            </div>
          </div>
         
        </div>
        <div class="modal-footer d-block text-center">  
          <a href="javascript:;"><button class="btn btn-primary" id="sendEmailMessage" type="button">Send</button></a> 
        </div>
      </form>
    </div>
  </div>
</div>



<div id="msgUser" class="modal fade " role="dialog">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">

        <h4>
          <span class="text2">Send message </span>
        </h4>
        <a class="close" data-dismiss="modal">×</a>
      </div>
      <form id="theForm">
        <input type="hidden" class="userMsgId" >

        <div class="modal-body">  

          <div class="alert alert-success alert-dismissible fade show successMsg" style="display:none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
          <div class="alert alert-danger alert-dismissible fade show errorMsg" style="display:none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>


          <div class="form-group">
            <div class="row">
              <div class="col-sm-3">
                <label>Message</label>
              </div>
              <div class="col-sm-9">
                <textarea class="form-control msgText" placeholder="Type your message here ..."  ></textarea>
                <span class="error errorStyle"><p id="emailMsgtext_error"></p></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-sm-3">
              </div>
              <div class="col-sm-9">
                <input type="radio"  value="info" id="banner" name="banner">
                <label for="info">Info</label>
                <input type="radio" value="success" id="banner" name="banner">
                <label for="success">Success</label>
                <input type="radio"  value="warning" id="banner" name="banner">
                <label for="warning">Warning</label>
                <input type="radio"  value="danger" id="banner" name="banner">
                <label for="danger">Danger</label>

                 <span class="error errorStyle"><p id="banner_Error"></p></span>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer d-block text-center">  
          <a href="javascript:;"><button class="btn btn-primary" id="sendMessage" type="button">Send</button></a> 
        </div>
      </form>
    </div>
  </div>
</div>




<div id="refundModal" class="modal fade " role="dialog">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">

        <h4>
          <span class="text2">Refund a Transaction </span>
        </h4>
        <a class="close" data-dismiss="modal">×</a>
      </div>
      <form id="refundForm">

        <div class="modal-body">  

          <div class="alert alert-success alert-dismissible fade show successMsg" style="display:none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
          <div class="alert alert-danger alert-dismissible fade show errorMsg" style="display:none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
        <input type="hidden" class="subscription_id" name="subscription_id" />
        <input type="hidden" class="refund_type" name="refund_type" />

         
          <div class="form-group">
            <div class="row">
              <div class="col-sm-3">
                <label>Refund Type</label>
              </div>
              <div class="col-sm-9">
                <input type="radio"  value="full" id="refund_type" name="refund_type" checked>
                <label for="full">Full</label>
                <input type="radio" value="partial" id="refund_type" name="refund_type">
                <label for="partial">Partial</label>

              </div>
            </div>
          </div>


           <div class="form-group">
            <div class="row">
              <div class="col-sm-3">
              </div>
              <div class="col-sm-9">
                <input type="text" class="form-control refundAmt" id="amount_to_refund">
                <p class="refundTxt"></p>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer d-block text-center">  
          <a href="javascript:;"><button class="btn btn-primary" id="refundTransaction" type="button">Refund</button></a> 
        </div>
      </form>
    </div>
  </div>
</div>