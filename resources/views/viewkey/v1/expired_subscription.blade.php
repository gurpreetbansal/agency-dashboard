@extends('layouts.view_key_layout')
@section('content')
<!-- Project Tabs Content -->
<div class="tab-content ">
<div class="white-box pa-0 mb-40">
   <div class="white-box-body emailAlert">
      <h3><img src="{{URL::asset('public/vendor/internal-pages/images/alert-icon.png')}}"> ALERT</h3>
      <?php
      if($user_id <> null){
      $email = App\User::get_agency_owner_email($user_id);
      ?>
      <p>Subscription has expired, contact your agency owner </p>
      <a href="mailto: {{$email}}"><button class="btn blue-btn"> Contact</button></a>
      <?php }?>
   </div>
</div>
</div>
<!-- Project Tabs Content End -->
</div>
@endsection