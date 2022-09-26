<!--Organic Keyword Growth Row -->
<div class="white-box pa-0 mb-40">
    <div class="white-box-head">
        <!-- <span class="white-box-handle" uk-icon="icon: table"></span> -->
        <div class="left">
            <div class="heading">
                <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
                <h2>Organic Keyword Growth
                    <span uk-tooltip="title: This section shows growth in organic keywords month after month, We show total keywords in Top3, Top 10, Top 20, Top 30 and Top 100 that your domain is ranking for.; pos: top-left" class="fa fa-info-circle"></span>
                </h2>
            </div>
        </div>
    </div>

    <div class="white-box-body">
        <div uk-grid>
            <div class="uk-width-1-2@xl uk-width-1-1@l uk-width-1-1@m">
                <div id="keywords-canvas" class="chart h-345 ajax-loader">
                  <canvas id="new-keywordsCanvas" width="50" height="40" > </canvas>
                </div>
            </div>
          <div class="uk-width-1-2@xl uk-width-1-1@l uk-width-1-1@m">
              <div class="top-organic-keyword-table">
                <div class="top-organic-keyword-table-head">
                  <h6 class="top-key-organic ajax-loader">Top Organic Keywords <small class="total_count"></small></h6>
                  <span class="sideDashboardView">
                    <a href="#extraKeywords" aria-expanded="false" class="btn blue-btn btn-sm top-key-organic">View details</a>
                  </span>
               
                </div>
                <div class="table-responsive">
                  <table class="style1" id="extra_organic_keywords">
                    <thead>
                      <tr>
                        <th class="ajax-loader">Keyword</th>
                        <th class="ajax-loader">Pos.</th>
                        <th class="ajax-loader">Volume </th>
                        <th class="ajax-loader">CPC (USD)</th>
                        <th class="ajax-loader">Traffic %</th>
                      </tr>
                    </thead>
                    <tbody >
                        <tr>
                          <td class="ajax-loader">....</td>
                          <td class="ajax-loader">....</td>
                          <td class="ajax-loader">....</td>
                          <td class="ajax-loader">....</td>
                          <td class="ajax-loader">....</td>
                        </tr>
                        <tr>
                          <td class="ajax-loader">....</td>
                          <td class="ajax-loader">....</td>
                          <td class="ajax-loader">....</td>
                          <td class="ajax-loader">....</td>
                          <td class="ajax-loader">....</td>
                        </tr>
                        <tr>
                          <td class="ajax-loader">....</td>
                          <td class="ajax-loader">....</td>
                          <td class="ajax-loader">....</td>
                          <td class="ajax-loader">....</td>
                          <td class="ajax-loader">....</td>
                        </tr>

                    </tbody>
                  </table>
                </div>
              </div>
        </div>
      </div>
    </div>
</div>
<!--Organic Keyword Growth Row End -->