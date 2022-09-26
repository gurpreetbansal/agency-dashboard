<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Loading.....</title>
  <link rel="shortcut icon" href="{{URL::asset('public/front/img/favicon.png')}}" sizes="32x32" type="image/x-icon">
<style type="text/css">
    // Set the Stage
/*body {
   background: #E9E9E9;
   display: flex;
   align-items: center;
   justify-content: center;
}*/
body{
  margin: 0;
  background: #fff;
  color: #202124;
}

.wrapper {
  margin: 30px;
  padding: 30px;
  background: #fff;
  width: 360px;
  height: 640px;
  display: flex;
  flex-direction: column;
}

.wrapper-cell {
   display: flex;
   margin-bottom: 30px;
}

// Animation
@keyframes placeHolderShimmer{
    0%{
        background-position: -468px 0
    }
    100%{
        background-position: 468px 0
    }
}

.animated-background {
    animation-duration: 1.25s;
    animation-fill-mode: forwards;
    animation-iteration-count: infinite;
    animation-name: placeHolderShimmer;
    animation-timing-function: linear;
    background: #F6F6F6;
    background: linear-gradient(to right, #F6F6F6 8%, #F0F0F0 18%, #F6F6F6 33%);
    background-size: 800px 104px;
    height: 96px;
    position: relative;
}

// Page Elements
.image {
  height: 60px;
  width: 60px;
//  background: #F6F6F6;
  @extend .animated-background;
}

.text {
  margin-left: 20px
}

.text-line {
  height: 10px;
  width: 230px;
  background: #F6F6F6;
  margin: 4px 0;
  @extend .animated-background;
}

@keyframes anim {
  0% {
    background-position: -468px 0;
  }
  100% {
    background-position: 468px 0;
  }
}
@-o-keyframes anim {
  0% {
    background-position: -468px 0;
  }
  100% {
    background-position: 468px 0;
  }
}
@-ms-keyframes anim {
  0% {
    background-position: -468px 0;
  }
  100% {
    background-position: 468px 0;
  }
}
@-moz-keyframes anim {
  0% {
    background-position: -468px 0;
  }
  100% {
    background-position: 468px 0;
  }
}
@-webkit-keyframes anim {
  0% {
    background-position: -468px 0;
  }
  100% {
    background-position: 468px 0;
  }
}

.post {
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  width: 496px;
  height: 326px;
  padding: 10px;
  margin: 10px auto;
  -moz-border-radius: 3px;
  -webkit-border-radius: 3px;
  border-radius: 3px;
  background-color: #fff;
  border: 1px solid;
  border-color: #e5e6e9 #dfe0e4 #d0d1d5;
}

.panel-effect {
  position: relative;
  background: #f6f7f8 no-repeat 800px 104px;
  background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4gPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PGxpbmVhckdyYWRpZW50IGlkPSJncmFkIiBncmFkaWVudFVuaXRzPSJvYmplY3RCb3VuZGluZ0JveCIgeDE9IjAuMCIgeTE9IjAuNSIgeDI9IjEuMCIgeTI9IjAuNSI+PHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2Y2ZjdmOCIvPjxzdG9wIG9mZnNldD0iMjAlIiBzdG9wLWNvbG9yPSIjZWRlZWYxIi8+PHN0b3Agb2Zmc2V0PSI0MCUiIHN0b3AtY29sb3I9IiNmNmY3ZjgiLz48c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmNmY3ZjgiLz48L2xpbmVhckdyYWRpZW50PjwvZGVmcz48cmVjdCB4PSIwIiB5PSIwIiB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2dyYWQpIiAvPjwvc3ZnPiA=');
  background-size: 100%;
  background-image: -webkit-gradient(linear, 0% 50%, 100% 50%, color-stop(0%, #f6f7f8), color-stop(20%, #edeef1), color-stop(40%, #f6f7f8), color-stop(100%, #f6f7f8));
  background-image: -moz-linear-gradient(left, #f6f7f8 0%, #edeef1 20%, #f6f7f8 40%, #f6f7f8 100%);
  background-image: -webkit-linear-gradient(left, #f6f7f8 0%, #edeef1 20%, #f6f7f8 40%, #f6f7f8 100%);
  background-image: linear-gradient(to right, #f6f7f8 0%, #edeef1 20%, #f6f7f8 40%, #f6f7f8 100%);
  height: 100%;
  width: 100%;
  -moz-animation: anim 1s forwards infinite linear;
  -webkit-animation: anim 1s forwards infinite linear;
  animation: anim 1s forwards infinite linear;
}

.fake-effect {
  position: absolute;
  background: #fff;
  right: 0;
  left: 0;
  height: 6px;
}

.fe-0 {
  height: 40px;
  left: 40px;
  width: 8px;
}

.fe-1 {
  height: 8px;
  left: 48px;
  top: 0;
  right: 0;
}

.fe-2 {
  left: 136px;
  top: 8px;
}

.fe-3 {
  height: 12px;
  left: 48px;
  top: 14px;
}

.fe-4 {
  left: 100px;
  top: 26px;
}

.fe-5 {
  height: 10px;
  left: 48px;
  top: 32px;
}

.fe-6 {
  height: 20px;
  top: 40px;
}

.fe-7 {
  left: 410px;
  top: 60px;
}

.fe-8 {
  height: 13px;
  top: 66px;
}

.fe-9 {
  left: 440px;
  top: 79px;
}

.fe-10 {
  height: 13px;
  top: 85px;
}

.fe-11 {
  left: 178px;
  top: 98px;
}

.loader-header{
  display: flex;
  border-bottom: solid 1px #ebebeb;
  padding: 24px 0 10px;
}

.loader-header .elem-left{
  width: 10%;
}

.loader-header .elem-right{
  width: 90%;
}

.loader-header .loader-logo{
  width: 92px;
  height:30px;
  margin: 0px 28px 0 30px;
}

article .cover{
  margin-bottom:40px;
}

</style>


<link rel="stylesheet" href="{{URL::asset('public/vendor/internal-pages/css/spyglass.css?v='.time())}}">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script defer src="{{URL::asset('public/vendor/internal-pages/js/developerjs/spyglass.js?v='.time())}}"></script>

</head>
<body>


<input type="hidden" name="key" id="keyenc" value="{{ $domain }}">
<input type="hidden" name="baseurl" id="baseurl" value="{{ config('app.base_url') }}">

<div class="page-loader">
  <div class="loader-header">
    <div class="elem-left">
      <div class="loader-logo">
        <div class="panel-effect"></div>
      </div>
    </div>
    <div class="elem-right">
      <div class="panel-effect" style="margin-bottom: 10px; max-width: 715px;height: 44px;"></div>
      <div class="panel-effect" style="margin-bottom: 10px; max-width: 715px;height: 29px;"></div>
    </div>

  </div>

  <div class="loader-body">
      <article style="padding:10px 0;width:100%; max-width:650px;margin-left:10%;">
        <div class="panel-effect" style="margin-bottom: 20px; max-width: 210px;height: 18px;"></div>

        <div class="cover">
          <div class="panel-effect" style="margin-bottom: 5px; max-width: 340px;height: 15px;"></div>
          <div class="panel-effect" style="margin-bottom: 8px; max-width: 60%;height: 22px;"></div>
          <div class="panel-effect" style="margin-bottom: 5px; max-width: 160px;height: 15px;"></div>
          <div class="panel-effect" style="margin-bottom: 5px; max-width: 100%;height: 15px;"></div>
          <div class="panel-effect" style="margin-bottom: 5px; max-width:80%;height: 15px;"></div>
        </div>

        <div class="cover">
          <div class="panel-effect" style="margin-bottom: 5px; max-width: 340px;height: 15px;"></div>
          <div class="panel-effect" style="margin-bottom: 8px; max-width: 60%;height: 22px;"></div>
          <div class="panel-effect" style="margin-bottom: 5px; max-width: 160px;height: 15px;"></div>
          <div class="panel-effect" style="margin-bottom: 5px; max-width: 100%;height: 15px;"></div>
          <div class="panel-effect" style="margin-bottom: 5px; max-width:80%;height: 15px;"></div>
        </div>

        <div class="cover">
          <div class="panel-effect" style="margin-bottom: 5px; max-width: 340px;height: 15px;"></div>
          <div class="panel-effect" style="margin-bottom: 8px; max-width: 60%;height: 22px;"></div>
          <div class="panel-effect" style="margin-bottom: 5px; max-width: 160px;height: 15px;"></div>
          <div class="panel-effect" style="margin-bottom: 5px; max-width: 100%;height: 15px;"></div>
          <div class="panel-effect" style="margin-bottom: 5px; max-width:80%;height: 15px;"></div>
        </div>

        <div class="cover">
          <div class="panel-effect" style="margin-bottom: 5px; max-width: 340px;height: 15px;"></div>
          <div class="panel-effect" style="margin-bottom: 8px; max-width: 60%;height: 22px;"></div>
          <div class="panel-effect" style="margin-bottom: 5px; max-width: 160px;height: 15px;"></div>
          <div class="panel-effect" style="margin-bottom: 5px; max-width: 100%;height: 15px;"></div>
          <div class="panel-effect" style="margin-bottom: 5px; max-width:80%;height: 15px;"></div>
        </div>

        <div class="cover">
          <div class="panel-effect" style="margin-bottom: 5px; max-width: 340px;height: 15px;"></div>
          <div class="panel-effect" style="margin-bottom: 8px; max-width: 60%;height: 22px;"></div>
          <div class="panel-effect" style="margin-bottom: 5px; max-width: 160px;height: 15px;"></div>
          <div class="panel-effect" style="margin-bottom: 5px; max-width: 100%;height: 15px;"></div>
          <div class="panel-effect" style="margin-bottom: 5px; max-width:80%;height: 15px;"></div>
        </div>


      </article>
  </div>
</div>



</body>
</html>