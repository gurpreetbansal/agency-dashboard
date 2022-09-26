<div class="app-inner-layout__header-boxed p-0">
    <div class="app-inner-layout__header page-title-icon-rounded text-white bg-premium-dark mb-4">
        <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <?php
                            if(isset($profile_data->logo_data['return_path']) && !empty($profile_data->logo_data['return_path'])){
                        ?>
                        <div class="brand-logo">
                                <img src="{{$profile_data->logo_data['return_path']}}" alt="campaign-logo" style="margin-right:10px;">
                            </div>
                        <?php
                            }else{
                            ?>
                            <div class="brand-logo">
                                <img src="{{URL::asset('/public/vendor/images/brand_logo.png')}}" alt="campaign-logo" >
                            </div>
                        <?php }  ?>
                        
                        <div>
                            <?php echo @$profile_data->domain_name;   ?>
                            </br>
                         
                                <div class="page-title-subheading">
                                    <?php 
                                        if(isset($profile_data->ProfileInfo) && !empty($profile_data->ProfileInfo)){
                                    ?>
                                    <a href="tel:<?php echo $profile_data->ProfileInfo->contact_no;?>"><i class="fa fa-phone" aria-hidden="true"></i>&nbsp; <?php echo $profile_data->ProfileInfo->contact_no;?></a> &nbsp;&nbsp;
                                    
                                    <a href="mailto:<?php echo $profile_data->ProfileInfo->email;?>"><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp; <?php echo $profile_data->ProfileInfo->email;?></a>
                                     <?php } ?>
                                </div>
            					
            					
                        </div>
                    </div>
                    <div class="page-title-actions">
                        <div class="d-inline-block dropdown">
                        </div>
                    </div>   
                </div>
        </div>
    </div>   
</div>
