<input type="hidden" class="page_no" value="{{ $page }}">
<div class="tabs site-audit-breadcrum">
    
    <ul class="breadcrumb-list">
        <li class="breadcrumb-item"><a href="javascript:;" class="seoHome"><i aria-hidden="true" class="fa fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="javascript:;" class="auditHome" >Site Audit</a></li>
        <li class="breadcrumb-item"><a href="javascript:;" class="viewPages"  data-filter="" >Issues</a></li>
        <li class="uk-active breadcrumb-item">Page Audit</li>
     </ul>
     <div class="btn-group">
        
        <!-- <a href="#" class="btn icon-btn color-red" uk-tooltip="title: Generate PDF File; pos: top-center"><img src="{{ URL::asset('/public/vendor/internal-pages/images/pdf-icon.png') }}"></a> -->

        <!-- <a target="_blank" href="{{url('/project-settings/'.$campaign_id)}}" class="btn icon-btn color-blue" uk-tooltip="title:Project Setting; pos: top-center"><img src="{{ URL::asset('/public/vendor/internal-pages/images/setting-icon.png') }} "></a>
        
        <a href="javascript:;" id="ShareKey" data-id="{{ $campaign_id }}" class="btn icon-btn color-purple" uk-tooltip="title: Generate Shared Key; pos: top-center" aria-expanded="false" ><img src="{{ URL::asset('/public/vendor/internal-pages/images/share-key-icon.png') }}"></a> -->

    </div>

</div>

<div class="uk-grid">
    <div class="uk-width-4-5@m">
        <div class="audit-white-box details-overview internal-design">
            <div class="elem-flex">
                <!-- <canvas id="myChart" width="50" height="50"></canvas> -->
                <div class="elem-start">
                    <div class="circle_percent ajax-loader"></div>
                    <div class="score-for">
                        <h2 class="ajax-loader h-91"></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="custom-width uk-flex">
                    <div class="audit-stats-box red ajax-loader" style="height: 117px"></div>
                    <div class="audit-stats-box yellow ajax-loader" style="height: 117px"></div>                    
                    <div class="elem-end">
                        <article>
                            <ul>
                                <li class="ajax-loader h-20"></li>
                                <li class="ajax-loader h-20"></li>
                                <li class="ajax-loader h-20"></li>
                            </ul>
                        </article>
                    </div>
                </div>
            </div>
        </div>

        <div class="audit-details-page">
            <div class="mt-4">
                <div class="auditdetail-issues">
                    <div class="audit-white-box mb-40 pa-0">
                        <div class="audit-box-head">
                            <h2 class="ajax-loader h-33"></h2>
                        </div>
                        <div class="audit-box-body">
                            <div class="border-bottom py-4">
                                <h5 class="uk-text-light px-4 mb-0 py-1">
                                    <div class="ajax-loader h-27" style="max-width: 600px">
                                </h5>

                               
                                <div class="px-4 py-2">
                                    <div class="uk-grid">
                                        <div class="uk-width-2-4">
                                            <div class="ajax-loader h-27" style="max-width: 400px"></div>
                                        </div>
                                        <div class="uk-width-2-4">
                                            <div class="ajax-loader h-27" style="max-width: 400px"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="px-4 py-2">
                                    <div class="uk-grid">
                                        <div class="uk-width-2-4">
                                            <div class="ajax-loader h-27" style="max-width: 400px"></div>
                                        </div>
                                        <div class="uk-width-2-4">
                                            <div class="ajax-loader h-27" style="max-width: 400px"></div>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>

                            <div class="py-4">
                                <h5 class="uk-text-light px-4 mb-0 py-1">
                                    <div class="ajax-loader h-27" style="max-width: 600px">
                                </h5>

                                
                                <div class="px-4 py-2">
                                    <div class="uk-grid">
                                        <div class="uk-width-2-4">
                                            <div class="ajax-loader h-27" style="max-width: 400px"></div>
                                        </div>
                                        <div class="uk-width-2-4">
                                            <div class="ajax-loader h-27" style="max-width: 400px"></div>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="px-4 py-2">
                                    <div class="uk-grid">
                                        <div class="uk-width-2-4">
                                            <div class="ajax-loader h-27" style="max-width: 400px"></div>
                                        </div>
                                        <div class="uk-width-2-4">
                                            <div class="ajax-loader h-27" style="max-width: 400px"></div>
                                        </div>
                                    </div>
                                </div>
                                

                            </div>
                        </div>
                    </div>

                    <div class="audit-white-box mb-40 pa-0">
                        <div class="audit-box-head">
                            <h2 class="ajax-loader h-33"></h2>
                        </div>
                        <div class="audit-box-body">
                            <div class="border-bottom py-4">
                                <h5 class="uk-text-light px-4 mb-0 py-1">
                                    <div class="ajax-loader h-27" style="max-width: 600px">
                                </h5>
                                <ul uk-accordion class="content-accr p-0 m-0">
                                    <li>
                                        <div class="uk-accordion-title px-4 py-2">
                                            <div class="uk-grid">
                                                <div class="uk-width-1-4">
                                                    <div class="ajax-loader h-27" style="max-width: 150px"></div>
                                                </div>
                                                <div class="uk-width-3-4 text-success">
                                                    <div class="ajax-loader h-27" style="max-width: 350px"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-accordion-content p-4 mt-0">
                                            <p class="ajax-loader h-27"></p>
                                            <ol>
                                                <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                            </ol>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="uk-accordion-title px-4 py-2">
                                            <div class="uk-grid">
                                                <div class="uk-width-1-4">
                                                    <div class="ajax-loader h-27" style="max-width: 150px"></div>
                                                </div>
                                                <div class="uk-width-3-4 text-success">
                                                    <div class="ajax-loader h-27" style="max-width: 350px"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-accordion-content p-4 mt-0">
                                            <p class="ajax-loader h-27"></p>
                                            <ol>
                                                <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                            </ol>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="auditdetail-links">
                    <div class="audit-white-box mb-40 pa-0">
                        <div class="audit-box-head">
                            <h2 class="ajax-loader h-33"></h2>
                        </div>
                        <div class="audit-box-body">
                            <div class="border-bottom py-4">
                                <ul uk-accordion class="content-accr p-0 m-0">
                                    <li>
                                        <div class="uk-accordion-title px-4 py-2">
                                            <div class="uk-grid">
                                                <div class="uk-width-1-4">
                                                    <div class="ajax-loader h-27" style="max-width: 150px"></div>
                                                </div>
                                                <div class="uk-width-3-4 text-success">
                                                    <div class="ajax-loader h-27" style="max-width: 350px"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-accordion-content p-4 mt-0">
                                            <p class="ajax-loader h-27"></p>
                                            <ol>
                                                <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                            </ol>
                                        </div>
                                    </li>
                                </ul>

                                <div class="px-4 py-2">
                                    <div class="uk-grid">
                                        <div class="uk-width-1-4">
                                            <div class="ajax-loader h-27" style="max-width: 150px"></div>
                                        </div>
                                        <div class="uk-width-3-4 text-success">
                                            <div class="ajax-loader h-27" style="max-width: 350px"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border-bottom py-4">
                                <h5 class="uk-text-light px-4 mb-0 py-1 ">
                                    <div class="ajax-loader h-27" style="max-width: 600px">
                                </h5>
                                <ul uk-accordion class="content-accr p-0 m-0 ">
                                    <li>
                                        <div class="uk-accordion-title px-4 py-2 ">
                                            <div class="uk-grid ">
                                                <div class="uk-width-1-4">
                                                    <div class="ajax-loader h-27" style="max-width: 150px"></div>
                                                </div>
                                                <div class="uk-width-3-4 text-success">
                                                    <div class="ajax-loader h-27" style="max-width: 350px"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-accordion-content p-4 mt-0 ">
                                            <p class="ajax-loader h-27"></p>
                                            <ol>
                                                <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                            </ol>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="audit-white-box mb-40 pa-0" id="insights">
                    <div class="audit-box-head">
                        <h2>Google PageSpeed Insights</h2>
                    </div>
                    <div class="audit-box-body">
                        <div class="speed-tab">
                            <ul class="uk-tab mb-0" data-uk-switcher="{connect:'#my-id'}">
                                <li><a href="">Desktop</a></li>
                                <li><a href="">Mobile</a></li>
                            </ul>

                            <ul id="my-id" class="uk-switcher">

                                <li id="insights-desktop">
                                    <div class="elem-flex border-bottom py-4">
                                        <div class="elem-start uk-width-expand uk-flex-middle px-4">
                                            <div class="circle_percent ajax-loader" data-percent="0" style="display: block;"></div>
                                            <div class="score-for">
                                                <h2><small class="ajax-loader h-20" style="max-width: 420px"></small></h2>
                                                <div
                                                    class="border uk-border-rounded py-1 px-3 uk-width-1-3 uk-flex-between uk-flex mt-4">
                                                    <div class="uk-flex uk-flex-middle">
                                                        <div class="ajax-loader h-27" style="width: 65px"></div>
                                                    </div>
                                                    <div class="uk-flex uk-flex-middle">
                                                        <div class="ajax-loader h-27" style="width: 65px"></div>
                                                    </div>
                                                    <div class="uk-flex uk-flex-middle">
                                                        <div class="ajax-loader h-27" style="width: 65px"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-bottom py-4">
                                        <h6 class="uk-text-normal px-4 mb-0 py-1">
                                            <div class="ajax-loader h-27" style="max-width: 600px">
                                        </h6>
                                        <ul uk-accordion="" class="content-accr p-0 m-0 uk-accordion">
                                            <li>
                                                <div class="uk-accordion-title px-4 py-3 border-bottom">
                                                    <div class="uk-grid ">
                                                        <div class="uk-width-1-3">
                                                            <div class="ajax-loader h-27" style="max-width: 150px"></div>
                                                        </div>
                                                        <div class="uk-width-2-3 text-success">
                                                            <div class="ajax-loader h-27" style="max-width: 350px"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-accordion-content p-4 mt-0 " hidden="">
                                                    <p class="ajax-loader h-27"></p>
                                                    <ol>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                    </ol>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="uk-accordion-title px-4 py-3 border-bottom">
                                                    <div class="uk-grid ">
                                                        <div class="uk-width-1-3">
                                                            <div class="ajax-loader h-27" style="max-width: 150px"></div>
                                                        </div>
                                                        <div class="uk-width-2-3 text-success">
                                                            <div class="ajax-loader h-27" style="max-width: 350px"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-accordion-content p-4 mt-0 " hidden="">
                                                    <p class="ajax-loader h-27"></p>
                                                    <ol>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                    </ol>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="px-4 mt-4">
                                            <p class="ajax-loader h-27"></p>
                                            <div class="data-img" style="margin-top: 20px;">
                                                <div class="uk-flex uk-flex-wrap uk-flex-wrap-around">
                                                    <span class="ajax-loader"></span>
                                                    <span class="ajax-loader"></span>
                                                    <span class="ajax-loader"></span>
                                                    <span class="ajax-loader"></span>
                                                    <span class="ajax-loader"></span>
                                                    <span class="ajax-loader"></span>
                                                    <span class="ajax-loader"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-bottom py-4">
                                        <h6 class="uk-text-normal px-4 mb-0 py-1">
                                            <div class="ajax-loader h-27" style="max-width: 600px">
                                        </h6>
                                        <div class="px-4 py-3">
                                            <div class="uk-grid">
                                                <div class="uk-width-1-3">
                                                    <div class="ajax-loader h-27" style="max-width: 150px"></div>
                                                </div>
                                                <div class="uk-width-2-3 text-success">
                                                    <div class="ajax-loader h-27" style="max-width: 350px"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-4 py-3">
                                            <div class="uk-grid">
                                                <div class="uk-width-1-3">
                                                    <div class="ajax-loader h-27" style="max-width: 150px"></div>
                                                </div>
                                                <div class="uk-width-2-3 text-success">
                                                    <div class="ajax-loader h-27" style="max-width: 350px"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-4 py-3">
                                            <div class="uk-grid">
                                                <div class="uk-width-1-3">
                                                    <div class="ajax-loader h-27" style="max-width: 150px"></div>
                                                </div>
                                                <div class="uk-width-2-3 text-success">
                                                    <div class="ajax-loader h-27" style="max-width: 350px"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-4 py-3">
                                            <div class="uk-grid">
                                                <div class="uk-width-1-3">
                                                    <div class="ajax-loader h-27" style="max-width: 150px"></div>
                                                </div>
                                                <div class="uk-width-2-3 text-success">
                                                    <div class="ajax-loader h-27" style="max-width: 350px"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-bottom py-4">
                                        <h6 class="uk-text-normal px-4 mb-0 py-1">
                                            <div class="ajax-loader h-27" style="max-width: 600px">
                                        </h6>
                                        <ul uk-accordion="" class="content-accr p-0 m-0 uk-accordion">
                                            <li>
                                                <div class="uk-accordion-title px-4 py-3 border-bottom">
                                                    <div class="uk-grid ">
                                                        <div class="uk-width-1-1">
                                                            <div class="ajax-loader h-27" style="max-width: 400px"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-accordion-content p-4 mt-0 " hidden="">
                                                    <p class="ajax-loader h-27"></p>
                                                    <ol>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                    </ol>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="uk-accordion-title px-4 py-3 border-bottom">
                                                    <div class="uk-grid ">
                                                        <div class="uk-width-1-1">
                                                            <div class="ajax-loader h-27" style="max-width: 400px"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-accordion-content p-4 mt-0 " hidden="">
                                                    <p class="ajax-loader h-27"></p>
                                                    <ol>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                    </ol>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="uk-accordion-title px-4 py-3 border-bottom">
                                                    <div class="uk-grid ">
                                                        <div class="uk-width-1-1">
                                                            <div class="ajax-loader h-27" style="max-width: 400px"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-accordion-content p-4 mt-0 " hidden="">
                                                    <p class="ajax-loader h-27"></p>
                                                    <ol>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                    </ol>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="uk-accordion-title px-4 py-3 border-bottom">
                                                    <div class="uk-grid ">
                                                        <div class="uk-width-1-1">
                                                            <div class="ajax-loader h-27" style="max-width: 400px"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-accordion-content p-4 mt-0 " hidden="">
                                                    <p class="ajax-loader h-27"></p>
                                                    <ol>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                    </ol>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="uk-accordion-title px-4 py-3 border-bottom">
                                                    <div class="uk-grid ">
                                                        <div class="uk-width-1-1">
                                                            <div class="ajax-loader h-27" style="max-width: 400px"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-accordion-content p-4 mt-0 " hidden="">
                                                    <p class="ajax-loader h-27"></p>
                                                    <ol>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                    </ol>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="py-4">
                                        <ul uk-accordion="" class="content-accr p-0 m-0 uk-accordion">
                                            <li>
                                                <div class="uk-accordion-title px-4 py-2">
                                                    <div class="uk-grid">
                                                        <div class="uk-width-1-3">
                                                            <div class="ajax-loader h-27" style="max-width: 150px"></div>
                                                        </div>
                                                        <div class="uk-width-2-3 text-success">
                                                            <div class="ajax-loader h-27" style="max-width: 350px"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-accordion-content p-4 mt-0" hidden="">
                                                    <p class="ajax-loader h-27"></p>
                                                    <ol>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                    </ol>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li id="insights-mobile">
                                    <div class="elem-flex border-bottom py-4">
                                        <div class="elem-start uk-width-expand uk-flex-middle px-4">
                                            <div class="circle_percent ajax-loader" data-percent="0" style="display: block;"></div>
                                            <div class="score-for">
                                                <h2><small class="ajax-loader h-20" style="max-width: 420px"></small></h2>
                                                <div
                                                    class="border uk-border-rounded py-1 px-3 uk-width-1-3 uk-flex-between uk-flex mt-4">
                                                    <div class="uk-flex uk-flex-middle">
                                                        <div class="ajax-loader h-27" style="width: 65px"></div>
                                                    </div>
                                                    <div class="uk-flex uk-flex-middle">
                                                        <div class="ajax-loader h-27" style="width: 65px"></div>
                                                    </div>
                                                    <div class="uk-flex uk-flex-middle">
                                                        <div class="ajax-loader h-27" style="width: 65px"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-bottom py-4">
                                        <h6 class="uk-text-normal px-4 mb-0 py-1">
                                            <div class="ajax-loader h-27" style="max-width: 600px">
                                        </h6>
                                        <ul uk-accordion="" class="content-accr p-0 m-0 uk-accordion">
                                            <li>
                                                <div class="uk-accordion-title px-4 py-3 border-bottom">
                                                    <div class="uk-grid ">
                                                        <div class="uk-width-1-3">
                                                            <div class="ajax-loader h-27" style="max-width: 150px"></div>
                                                        </div>
                                                        <div class="uk-width-2-3 text-success">
                                                            <div class="ajax-loader h-27" style="max-width: 350px"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-accordion-content p-4 mt-0 " hidden="">
                                                    <p class="ajax-loader h-27"></p>
                                                    <ol>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                    </ol>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="uk-accordion-title px-4 py-3 border-bottom">
                                                    <div class="uk-grid ">
                                                        <div class="uk-width-1-3">
                                                            <div class="ajax-loader h-27" style="max-width: 150px"></div>
                                                        </div>
                                                        <div class="uk-width-2-3 text-success">
                                                            <div class="ajax-loader h-27" style="max-width: 350px"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-accordion-content p-4 mt-0 " hidden="">
                                                    <p class="ajax-loader h-27"></p>
                                                    <ol>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                    </ol>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="px-4 mt-4">
                                            <p class="ajax-loader h-27"></p>
                                            <div class="data-img" style="margin-top: 20px;">
                                                <div class="uk-flex uk-flex-wrap uk-flex-wrap-around">
                                                    <span class="ajax-loader"></span>
                                                    <span class="ajax-loader"></span>
                                                    <span class="ajax-loader"></span>
                                                    <span class="ajax-loader"></span>
                                                    <span class="ajax-loader"></span>
                                                    <span class="ajax-loader"></span>
                                                    <span class="ajax-loader"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-bottom py-4">
                                        <h6 class="uk-text-normal px-4 mb-0 py-1">
                                            <div class="ajax-loader h-27" style="max-width: 600px">
                                        </h6>
                                        <div class="px-4 py-3">
                                            <div class="uk-grid">
                                                <div class="uk-width-1-3">
                                                    <div class="ajax-loader h-27" style="max-width: 150px"></div>
                                                </div>
                                                <div class="uk-width-2-3 text-success">
                                                    <div class="ajax-loader h-27" style="max-width: 350px"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-4 py-3">
                                            <div class="uk-grid">
                                                <div class="uk-width-1-3">
                                                    <div class="ajax-loader h-27" style="max-width: 150px"></div>
                                                </div>
                                                <div class="uk-width-2-3 text-success">
                                                    <div class="ajax-loader h-27" style="max-width: 350px"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-4 py-3">
                                            <div class="uk-grid">
                                                <div class="uk-width-1-3">
                                                    <div class="ajax-loader h-27" style="max-width: 150px"></div>
                                                </div>
                                                <div class="uk-width-2-3 text-success">
                                                    <div class="ajax-loader h-27" style="max-width: 350px"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-4 py-3">
                                            <div class="uk-grid">
                                                <div class="uk-width-1-3">
                                                    <div class="ajax-loader h-27" style="max-width: 150px"></div>
                                                </div>
                                                <div class="uk-width-2-3 text-success">
                                                    <div class="ajax-loader h-27" style="max-width: 350px"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-bottom py-4">
                                        <h6 class="uk-text-normal px-4 mb-0 py-1">
                                            <div class="ajax-loader h-27" style="max-width: 600px">
                                        </h6>
                                        <ul uk-accordion="" class="content-accr p-0 m-0 uk-accordion">
                                            <li>
                                                <div class="uk-accordion-title px-4 py-3 border-bottom">
                                                    <div class="uk-grid ">
                                                        <div class="uk-width-1-1">
                                                            <div class="ajax-loader h-27" style="max-width: 400px"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-accordion-content p-4 mt-0 " hidden="">
                                                    <p class="ajax-loader h-27"></p>
                                                    <ol>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                    </ol>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="uk-accordion-title px-4 py-3 border-bottom">
                                                    <div class="uk-grid ">
                                                        <div class="uk-width-1-1">
                                                            <div class="ajax-loader h-27" style="max-width: 400px"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-accordion-content p-4 mt-0 " hidden="">
                                                    <p class="ajax-loader h-27"></p>
                                                    <ol>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                    </ol>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="uk-accordion-title px-4 py-3 border-bottom">
                                                    <div class="uk-grid ">
                                                        <div class="uk-width-1-1">
                                                            <div class="ajax-loader h-27" style="max-width: 400px"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-accordion-content p-4 mt-0 " hidden="">
                                                    <p class="ajax-loader h-27"></p>
                                                    <ol>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                    </ol>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="uk-accordion-title px-4 py-3 border-bottom">
                                                    <div class="uk-grid ">
                                                        <div class="uk-width-1-1">
                                                            <div class="ajax-loader h-27" style="max-width: 400px"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-accordion-content p-4 mt-0 " hidden="">
                                                    <p class="ajax-loader h-27"></p>
                                                    <ol>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                    </ol>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="uk-accordion-title px-4 py-3 border-bottom">
                                                    <div class="uk-grid ">
                                                        <div class="uk-width-1-1">
                                                            <div class="ajax-loader h-27" style="max-width: 400px"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-accordion-content p-4 mt-0 " hidden="">
                                                    <p class="ajax-loader h-27"></p>
                                                    <ol>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                    </ol>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="py-4">
                                        <ul uk-accordion="" class="content-accr p-0 m-0 uk-accordion">
                                            <li>
                                                <div class="uk-accordion-title px-4 py-2">
                                                    <div class="uk-grid">
                                                        <div class="uk-width-1-3">
                                                            <div class="ajax-loader h-27" style="max-width: 150px"></div>
                                                        </div>
                                                        <div class="uk-width-2-3 text-success">
                                                            <div class="ajax-loader h-27" style="max-width: 350px"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-accordion-content p-4 mt-0" hidden="">
                                                    <p class="ajax-loader h-27"></p>
                                                    <ol>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                        <li class="ajax-loader h-27" style="max-width: 350px"></li>
                                                    </ol>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="uk-width-1-5@m filters right-sidebar">
        <div>
            <h5 class="uk-text-medium ajax-loader h-33"></h5>
            <ul class="all-pages-con">
                <li class="ajax-loader h-20"></li>
                <li class="ajax-loader h-20"></li>
                <li class="ajax-loader h-20"></li>
                <li class="ajax-loader h-20"></li>
                <li class="ajax-loader h-20"></li>
                <li class="ajax-loader h-20"></li>
            </ul>
        </div>
    </div>
</div>