<div id="addNewProject-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">

        <h4>
          <div class="text1">Welcome,</div>
          <span class="text2">It only takes a <span class="text-success">few seconds</span> to add your project</span>
        </h4>
        <a class="close" data-dismiss="modal">×</a>
      </div>
      <form id="addProject" name="project" role="form">
        <div class="loader">
          <div class="ball-grid-pulse">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
          </div>  
        </div>

        <div class="modal-body">	
          <div class="alert alert-success alert-dismissible fade show successMsg" style="display:none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
          <div class="alert alert-danger alert-dismissible fade show errorMsg" style="display:none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>


          <div class="form-group">
           <div class="row">
            <div class="col-sm-4">
              <label>Project Name</label>
            </div>
            <div class="col-sm-8">
              <input type="text" name="domain_name" class="form-control newProjectdomain_name"  placeholder="Project Name" >
              <span class="error errorStyle"><p id="domain_name_error"></p></span>
            </div>
          </div>
        </div>
        <div class="form-group">
         <div class="row">
          <div class="col-sm-4">
            <label>Domain Url</label>
          </div>
          <div class="col-sm-8">
            <input type="text" name="domain_url" class="form-control newProjectdomain_url" placeholder="Domain Url: http://example.com">
            <p style="font-size: 11px;color: #3ac47d;">Please provide exact domain url to fetch records.</p>
            <span class="error errorStyle"><p id="domain_url_error"></p></span>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col-sm-4">
            <label>Regional Database</label>
          </div>
          <div class="col-sm-8">
            <select name="regional_db" id="regional_db" class="select form-control">
              <?php
              if (isset($regional_db) && !empty($regional_db)) {
                foreach ($regional_db as $db) {
                  ?>
                  <option value="{{$db->short_name}}" {{$db->short_name=='us'?'selected':''}}>{{$db->short_name .' ('.$db->long_name.')'}}</option>
                  <?php
                }
              }
              ?>
            </select>
          </div>
        </div>
      </div>	

      <div class="form-group">
       <div class="row">
        <div class="col-sm-4">
         <label>Select your Dashboards</label>

       </div>
       <div class="col-sm-8">
         <?php
         if (isset($dashboardTypes) && !empty($dashboardTypes)) {
          foreach ($dashboardTypes as $dashboard) {
           ?>


           <div class="row">
             <label>{{$dashboard->name}}</label>
             <div class="custom-control custom-switch">
              <input name="dashboardType[{{$dashboard->id}}]" type="checkbox" class="custom-control-input btn btn-primary dashboardType" id="customSwitches{{$dashboard->id}}" value="{{$dashboard->id}}">
              <label class="custom-control-label" for="customSwitches{{$dashboard->id}}"></label>
            </div>
          </div>

        <?php }}?>
      </div></div>


      <span class="error errorStyle"><p id="dashboardType_error"></p></span>
    </div>	

  </div>
  <div class="modal-footer d-block text-center">	
    <button class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg" id="submit_new_project" type="button">Submit</button>
  </div>
</form>
</div>
</div>
</div>





<div id="ConnectGoogleAnalyticsModal" class="modal fade " role="dialog">
  <?php 
  $currentRoute = \Request::segment(1);
  $campaignId = \Request::segment(2);
  ?>
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">

        <h4>
          <!--div class="text1"></div-->
          <span class="text2">Connect Your Services <span class="text-success">Google Analytics</span></span>
        </h4>
        <a class="close" data-dismiss="modal">×</a>
      </div>
      <form name="project" role="form">


        <div class="modal-body">	

          <div class="alert alert-success alert-dismissible fade show successMsg" style="display:none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
          <div class="alert alert-danger alert-dismissible fade show errorMsg" style="display:none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>

          <div class="form-group">
           <label>Choose An Existing Account</label>
           <select name="existing_accounts" id="existing_accounts" class="select form-control jsExistingAccounts">
            <option value="">Please Select</option>
            <?php
            if(!empty($getAccounts)){
             foreach($getAccounts as $account){
              ?>
              <option value="{{$account->id}}">{{$account->email}}</option>
            <?php } } ?>
          </select>
        </div>			

					<!-- <div class="text-right">
						<button type="button" class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg selectAnalyticID">
							Use This Account
						</button>
					</div>	 -->


          <div class="select-view-cover">
            <h2 id="view-name">Select a Campaign</h2> 

            <form name="save_view_data" id="save_view_data" method="post" >
              <div id="view-selector" class="chart">
                <div class="row">
                  <div class="col-sm-4">
                    <div class="form-group">
                      <select name="analytic_account" id="analytic_account" class="selectpicker form-control jsanalytic_account" data-live-search="true" data-dropup-auto="false" data-id="">
                        <option value=""><--select account--></option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <select name="analytic_property" id="analytic_property" class="selectpicker form-control jsanalytic_property">
                          <option value=""><--select property--></option>
                          </select>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <select name="analytic_view_id" id="analytic_view_id" data-id="" class="selectpicker form-control jsanalytic_view_id">
                            <option value=""><--select view id--></option>
                            </select>
                          </div>
                        </div>

                      </div>
                    </div>
                    <div class="text-right">
                      <button type="button" id="submit_button" value="Save" class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg saveData">
                        Save
                      </button>
                    </div>

                  </form>
                </div>




              </div>
              <div class="modal-footer d-block text-center">	
               <a href="{{ url('/connect_google_analytics?campaignId='.$campaignId.'&provider=google&redirectPage='.$currentRoute) }}"><button class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg" id="addNewGoogleAnalytics" type="button">Add New Account</button></a>
             </div>
           </form>
         </div>
       </div>
     </div>

     <div id="ConnectGoogleSearchConsoleModal" class="modal fade " role="dialog">
      <?php 
      $currentRoute = \Request::segment(1);
      $campaignId = \Request::segment(2);
      ?>
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">

            <h4>
              <span class="text2">Connect Your Services <span class="text-success">Google Search Console</span></span>
            </h4>
            <a class="close" data-dismiss="modal">×</a>
          </div>
          <form name="searchConsole" role="form">


            <div class="modal-body">	

              <div class="alert alert-success alert-dismissible fade show successMsg" style="display:none;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>
              <div class="alert alert-danger alert-dismissible fade show errorMsg" style="display:none;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>

              <div class="form-group">
               <label>Choose An Existing Account</label>
               <select name="existing_console_accounts" id="existing_console_accounts" class="select form-control jsExistingConsoleAccount">
                <option value="">Please Select</option>
                <?php
                if(!empty($getConsoleAccount)){
                 foreach($getConsoleAccount as $account){
                  ?>
                  <option value="{{$account->id}}">{{$account->email}}</option>
                <?php } } ?>
              </select>
            </div>			

            <div class="select-view-cover">
              <h2 id="view-name">Select a Campaign</h2> 

              <form name="save_console_view_data" id="save_console_view_data" method="post" >
                <div id="view-selector" class="chart">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <select name="console_account" id="console_account" class="selectpicker form-control jsConsoleAccount" data-live-search="true" data-dropup-auto="false" data-id="">
                          <option value=""><--Select Account--></option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="text-right">
                    <button type="button" id="submitt_button" value="Save" class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg saveConsoleData">
                      Save
                    </button>
                  </div>

                </form>
              </div>




            </div>
            <div class="modal-footer d-block text-center">	
             <a href="{{ url('/connect_search_console?campaignId='.$campaignId.'&redirectPage='.$currentRoute) }}"><button class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg" id="addNewGoogleSearchConsole" type="button">Add New Account</button></a>
           </div>
         </form>
       </div>
     </div>
   </div>

   <div id="ConnectGoogleAdsModal" class="modal fade " role="dialog">
    <?php 
    $currentRoute = \Request::segment(1);
    $campaignId = \Request::segment(2);
    ?>
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">

          <h4>
            <!--div class="text1"></div-->
            <span class="text2">Connect Your Services <span class="text-success">Google Ads</span></span>
          </h4>
          <a class="close" data-dismiss="modal">×</a>
        </div>
        <form id="addGoogleAds" name="project" role="form">


          <div class="modal-body">	

            <div class="alert alert-success alert-dismissible fade show successMsg" style="display:none;">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <div class="alert alert-danger alert-dismissible fade show errorMsg" style="display:none;">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>

            <div class="form-group">
             <label>Choose An Existing Account</label>
             <select name="existing_ads_accounts" id="existing_ads_accounts" class="select form-control">
              <option value="">Please Select</option>
              <?php
              if(!empty($getAdsAccounts)){
               foreach($getAdsAccounts as $ads_account){
                ?>
                <option value="{{$ads_account->id}}">{{$ads_account->email}}</option>
              <?php } } ?>
            </select>
          </div>			

          <div class="text-right">
            <button type="button" class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg selectAdsID">
             Use This Account
           </button>
         </div>	


         <div class="select-view-cover">
          <h2 id="view-name">Select a Account</h2> 

          <form name="save_ads_account" id="save_ads_account" method="post" >
            <div id="view-selector" class="chart">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <select name="ads_accounts" id="ads_accounts" class="selectpicker form-control" data-live-search="true" data-dropup-auto="false" data-id="">
                      <option value=""><--select account--></option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-right">
                <button type="button" id="submit_buttn" value="Save" class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg saveAdsData">
                  Save
                </button>
              </div>

            </form>
          </div>


        </div>
        <div class="modal-footer d-block text-center">	
         <a href="{{ url('/connect_google_ads?campaignId='.$campaignId.'&redirectPage='.$currentRoute) }}"><button class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg" id="addNewGoogleAds" type="button">New Account</button></a> 
       </div>
     </form>
   </div>
 </div>
</div>


<div id="shareModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">

        <h4>
          <div class="text1">Share</div>
        </h4>
        <a class="close" data-dismiss="modal">×</a>
      </div>
      <form role="form">


        <div class="modal-body">    
         <div class="form-group">
           <div class="row">
            <div class="col-sm-2">
              <label>Share key</label>
            </div>
            <div class="col-sm-10">
              <input type="text"  class="form-control" id="copy_share_key_value" readonly="readonly" value="">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer d-block">  

        <button class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg  copyText" id="copy_share_key" type="button">Copy Share Key</button>
        <span id="copy_text" style="display:none;color: #007bff;"></span>
      </div>
    </form>
  </div>
</div>
</div>


<div id="LocationModalKeyword" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">

        <h4>
          <div class="text1">Update Keyword(s)</div>
        </h4>
        <a class="close" data-dismiss="modal">×</a>
      </div>
      <form role="form">
        <div class="modal-body">    
         <div class="form-group">
           <div class="row">
            <div class="col-sm-2">
              <label>Search Engine Region</label>
            </div>
            <div class="col-sm-10">
              <select name="search_engine_region" class="select form-control regions" required id="update_region">
                <option value="">-Select-</option>
                <?php
                if(!empty($getRegions) && isset($getRegions) && count($getRegions)>0){
                  foreach($getRegions as $region){?>
                    <option value="{{$region->long_name}}" {{$region->short_name=='us'?'selected':''}}>{{$region->long_name}}</option>
                    <?php
                  }
                }
                ?>
              </select>
              <span class="error errorStyle"><p id="update_regions_error"></p></span>
            </div>
          </div>
        </div>

        <div class="form-group">
         <div class="row">
          <div class="col-sm-2">
            <label>Tracking Options</label>
          </div>
          <div class="col-sm-10">
            <select name="tracking_options" class="select form-control tracking_options" required id="update_tracking_options">
              <option value="">-Select-</option>
              <option value="desktop" selected>Desktop</option>
              <option value="mobile">Mobile</option>
            </select>
            <span class="error errorStyle"><p id="update_tracking_options_error"></p></span>
          </div>
        </div>
      </div>

      <div class="form-group">
       <div class="row">
        <div class="col-sm-2">
          <label>Language</label>
        </div>
        <div class="col-sm-10">
          <select name="language" class="select form-control language" required id="update_language">
            <option value="">-Select-</option>
            <option value="English" selected>English</option>
            <option value="French">French</option>
            <option value="Spanish">Spanish</option>
            <option value="Arabic">Arabic</option>
            <option value="Hebrew">Hebrew</option>
            <option value="Chinese">Chinese</option>
            <option value="Thailand">Thailand</option>
            <option value="Dutch">Dutch</option>
            <option value="Russian">Russian</option>
          </select>
          <span class="error errorStyle"><p id="update_language_error"></p></span>
        </div>
      </div>
    </div>

    <div class="form-group">
     <div class="row">
      <div class="col-sm-2">
        <label>Locations</label>
      </div>
      <div class="col-sm-10">
        <input name="locations" required="required" id="update_location" type="text" class="form-control " placeholder="Search" required />
        <input id="latUpdate" type="hidden" name="lat">
        <input id="longUpdate" type="hidden" name="long">
        <span class="error errorStyle"><p id="update_dfs_locations_error"></p></span>
      </div>
    </div>
  </div>

</div>
<div class="modal-footer d-block">  

  <button class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg" id="submitUpdateKeywords" type="button">Submit</button>
  <span id="copy_text" style="display:none;color: #007bff;"></span>
</div>
</form>
</div>
</div>
</div>


<!--Auth page pop-ups-->


<div id="AuthConnectGoogleSearchConsoleModal" class="modal fade " role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">

        <h4>
          <span class="text2">Connect Your Services <span class="text-success">Google Search Console</span></span>
        </h4>
        <a class="close" data-dismiss="modal">×</a>
      </div>
      <form name="searchConsole" role="form">

        <input type="hidden" class="consolecampaignId" >
        <input type="hidden" class="currentRoute" value="{{\Request::segment(1)}}">



        <div class="modal-body">  

          <div class="alert alert-success alert-dismissible fade show successMsg" style="display:none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
          <div class="alert alert-danger alert-dismissible fade show errorMsg" style="display:none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>

          <div class="row">

            <div class="form-group col-md-9">
             <label>Choose An Existing Account</label>
             <select name="existing_console_accounts" id="auth_existing_console_accounts" class="select form-control jsAuthexisting_console_accounts">
              <option value="">Please Select</option>
              <?php
              if(!empty($getConsoleAccount)){
               foreach($getConsoleAccount as $account){
                ?>
                <option value="{{$account->id}}">{{$account->email}}</option>
              <?php } } ?>
            </select>
          </div>      
          <div class="col-md-3">
            <button type="button" class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg auth_search_refresh"><i class="fa fa-refresh"></i> <img src="{{URl::asset('public/vendor/images/ajax-loader.gif')}}" class="update_google_loader" style="display:none;margin-right: 6px;" />Refresh</button>
          </div>
        </div>
        <div class="select-view-cover">
          <h2 id="view-name">Select a Campaign</h2> 

          <form name="save_console_view_data" id="save_console_view_data" method="post" >
            <div id="view-selector" class="chart">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <select name="console_account" id="auth_console_account" class="selectpicker form-control jsauth_console_account" data-live-search="true" data-dropup-auto="false" data-id="">
                      <option value=""><--select Account--></option>
                      </select>
                    </div>
                  </div>


                </div>
              </div>
              <div class="text-right">
                <button type="button"  value="Save" class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg auth_saveConsoleData">
                  Save
                </button>
              </div>

            </form>
          </div>




        </div>
        <div class="modal-footer d-block text-center">  
         <a href="javascript:;"><button class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg" id="addNewGoogleSearchConsoleAUth" type="button">Add New Account</button></a>
         
       </div>
     </form>
   </div>
 </div>
</div>

<div id="AuthConnectGoogleAnalyticsModal" class="modal fade " role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">

        <h4>
          <!--div class="text1"></div-->
          <span class="text2">Connect Your Services <span class="text-success">Google Analytics</span></span>
        </h4>
        <a class="close" data-dismiss="modal">×</a>
      </div>
      <form name="project" role="form">
       <input type="hidden" class="analyticcampaignId" >
       <input type="hidden" class="analyticcurrentRoute" value="{{\Request::segment(1)}}">

       <div class="modal-body">  

        <div class="alert alert-success alert-dismissible fade show successMsg" style="display:none;">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <div class="alert alert-danger alert-dismissible fade show errorMsg" style="display:none;">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <div class="row">
          <div class="col-md-9 form-group">
           <label>Choose An Existing Account</label>
           <select name="existing_accounts" id="auth_existing_accounts" class="select form-control jsAuthExistingAccounts">
            <option value="">Please Select</option>
            <?php
            if(!empty($getAccounts)){
             foreach($getAccounts as $account){
              ?>
              <option value="{{$account->id}}">{{$account->email}}</option>
            <?php } } ?>
          </select>
        </div>  
        <div class="col-md-3">
         <button type="button" class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg auth_analytic_refresh"><i class="fa fa-refresh"></i> <img src="{{URl::asset('public/vendor/images/ajax-loader.gif')}}" class="update_google_loader" style="display:none;margin-right: 6px;" />Refresh</button>
       </div>
     </div>    



     <div class="select-view-cover">
      <h2 id="view-name">Select a Campaign</h2> 

      <form name="save_view_data" id="save_view_data" method="post" >
        <div id="view-selector" class="chart">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <select name="analytic_account" id="auth_analytic_account" class="selectpicker form-control jsAuthAnalyticAccount" data-live-search="true" data-dropup-auto="false" data-id="">
                  <option value=""><--select account--></option>
                  </select>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <select name="analytic_property" id="auth_analytic_property" class="selectpicker form-control jsAuthAnalyticProperty">
                    <option value=""><--select property--></option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <select name="analytic_view_id" id="auth_analytic_view_id" data-id="" class="selectpicker form-control jsAuthAnalyticView">
                      <option value=""><--select view id--></option>
                      </select>
                    </div>
                  </div>

                </div>
              </div>
              <div class="text-right">
                <button type="button"  value="Save" class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg auth_saveData">
                  Save
                </button>

              </div>

            </form>
          </div>




        </div> 
        <div class="modal-footer d-block text-center">  
         <a href="javascript:;"><button class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg" id="AuthaddNewGoogleAnalytics" type="button">Add New Account</button></a>

       </div>
     </form>
   </div>
 </div>
</div>

<div id="AuthConnectGoogleAdsModal" class="modal fade " role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">

        <h4>
          <span class="text2">Connect Your Services <span class="text-success">Google Ads</span></span>
        </h4>
        <a class="close" data-dismiss="modal">×</a>
      </div>
      <form id="addGoogleAds" name="project" role="form">
        <input type="hidden" class="adscampaignId" >
        <input type="hidden" class="adscurrentRoute" value="{{\Request::segment(1)}}">

        <div class="modal-body">  

          <div class="alert alert-success alert-dismissible fade show successMsg" style="display:none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
          <div class="alert alert-danger alert-dismissible fade show errorMsg" style="display:none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>

          <div class="form-group">
           <label>Choose An Existing Account</label>
           <select name="existing_ads_accounts" id="auth_existing_ads_accounts" class="select form-control">
            <option value="">Please Select</option>
            <?php
            if(!empty($getAdsAccounts)){
             foreach($getAdsAccounts as $ads_account){
              ?>
              <option value="{{$ads_account->id}}">{{$ads_account->email}}</option>
            <?php } } ?>
          </select>
        </div>      

        


        <div class="select-view-cover">
          <h2 id="view-name">Select a Account</h2> 

          <form name="save_ads_account" id="save_ads_account" method="post" >
            <div id="view-selector" class="chart">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <select name="ads_accounts" id="auth_ads_accounts" class="selectpicker form-control" data-live-search="true" data-dropup-auto="false" data-id="">
                      <option value=""><--select account--></option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-right">
                <button type="button"  value="Save" class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg auth_saveAdsData">
                  Save
                </button>
              </div>

            </form>
          </div>


        </div>
        <div class="modal-footer d-block text-center">  
         <a href="javascript:;"><button class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg" id="AuthaddNewGoogleAds" type="button">New Account</button></a> 
       </div>
     </form>
   </div>
 </div>
</div>

<div id="Auth_tags" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">

        <h4>
         <div class="text1">Manage Tags</div>
         <span class="text2" style="font-size:12px;">You can split the tags into text with <strong>","</strong></span>
       </h4>
       <a class="close" data-dismiss="modal">×</a>
     </div>
     <form>
      <input type="hidden" class="campID" >

      <div class="modal-body">  

        <div class="alert alert-success alert-dismissible fade show successMsg" style="display:none;">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <div class="alert alert-danger alert-dismissible fade show errorMsg" style="display:none;">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>

        <div class="form-group">
          <!-- <textarea id="auth_tags" class="form-control"></textarea> -->
          <input type="text" data-role="tagsinput" value="" class="tags_auth">
        </div>      

        <div class="modal-footer d-block text-center">  
         <a href="javascript:;"><button class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg" id="saveTagsAuth" type="button">Done</button></a> 
       </div>
     </form>
   </div>
 </div>
</div>
</div>


<div id="Auth_GMB" class="modal fade " role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">

        <h4>
          <span class="text2">Connect Your Services <span class="text-success">Google My Business</span></span>
        </h4>
        <a class="close" data-dismiss="modal">×</a>
      </div>
      <form>
        <input type="hidden" class="gmbcampaignId" >
        <input type="hidden" class="gmbcurrentRoute" value="{{\Request::segment(1)}}">

        <div class="modal-body">  

          <div class="alert alert-success alert-dismissible fade show successMsg" style="display:none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
          <div class="alert alert-danger alert-dismissible fade show errorMsg" style="display:none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>

          <div class="row">

            <div class="form-group col-md-9">
             <label>Choose An Existing Account</label>
             <select name="existing_gmb_accounts" id="auth_existing_gmb_accounts" class="select form-control jsAuthexisting_gmb_accounts">
              <option value="">Please Select</option>
              <?php
              if(!empty($getgmbAccounts)){
               foreach($getgmbAccounts as $account){
                ?>
                <option value="{{$account->id}}">{{$account->email}}</option>
              <?php } } ?>
            </select>
          </div>      
          <div class="col-md-3">
            <button type="button" class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg auth_search_refresh"><i class="fa fa-refresh"></i> <img src="{{URl::asset('public/vendor/images/ajax-loader.gif')}}" class="update_google_loader" style="display:none;margin-right: 6px;" />Refresh</button>
          </div>
        </div>

        <div class="select-view-cover">
          <h2 id="view-name">Select a Location</h2> 

          <form name="save_console_view_data" id="save_console_view_data" method="post" >
            <div id="view-selector" class="chart">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <select name="gmb_account" id="auth_gmb_account" class="selectpicker form-control jsauth_gmb_account" data-live-search="true" data-dropup-auto="false" data-id="">
                      <option value=""><--select Location--></option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-right">
                <button type="button"  value="Save" class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg auth_saveGmbData">
                  Save
                </button>
              </div>
            </form>
          </div>

        <div class="modal-footer d-block text-center">  
         <a href="javascript:;"><button class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-success btn-lg" id="addNewGMBAuth" type="button">New Account</button></a> 
       </div>
     </form>
   </div>
 </div>
</div>
<!--Auth page pop-ups-->