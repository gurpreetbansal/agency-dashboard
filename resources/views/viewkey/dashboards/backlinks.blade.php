<div class="main-data-view" id="backlinks" uk-sortable="handle:.white-box-handle">
	 <!-- Backlink Profile Row -->
 <div class="white-box pa-0 mb-40">
  <div class="white-box-head">
  <!-- <span class="white-box-handle" uk-icon="icon: table"></span> -->
    <div class="left">
      <div class="heading">
        <img src="{{URL::asset('public/vendor/internal-pages/images/backlink-profile-img.png')}}">
        <h2>Backlink Profile
          <span uk-tooltip="title: This section shows growth in referring domains month after month, however we check the total number of referring domains on weekly basis and same can be seen in graph.; pos: top-left" class="fa fa-info-circle"></span>
        </h2>
      </div>
    </div>
  </div>

  <div class="white-box-body">
    <div uk-grid class="mb-40">
      <div class="uk-width-3-5@xl">
        <div class="chart-head">
          <h6>Referring Domains</h6>
           <div class="filter-list">
            <ul>
              <li><button type="button"  class="backlinkChart_View active" data-value="all">All Time</button></li>
              <li><button type="button"  class="backlinkChart_View" data-value="one_year">One Year</button></li>
              <li><button type="button"  class="backlinkChart_View " data-value="last_30">Last 30 Days</button></li>
            </ul>
          </div>
        </div>
        <div class="chart h-292">
          <input type="hidden" class="backlinkSelectdChart" value="all">

          <canvas id="chart-referring-domains-back"></canvas>
        </div>
      </div>
      <div class="uk-width-2-5@xl pl-2 pl-l">
        <div class="top-organic-keyword-table">
          <div class="top-organic-keyword-table-head">
            <h6>Summary</h6>
          </div>
          <div class="table-responsive">
            <table class="style1">
              <tbody>
                @if($flag == 1)
                <tr>
                  <td>Referring Domains</td>
                  <td>{{@$backlink_profile_summary->referringDomains}}</td>
                </tr>
                <tr>
                  <td>Referring sub-domains</td>
                  <td>{{@$backlink_profile_summary->referringSubDomains}}</td>
                </tr>
                <tr>
                  <td>Referring Ips</td>
                  <td>{{@$backlink_profile_summary->referringIps}}</td>
                </tr>
                <tr>
                  <td>Referring Links</td>
                  <td>{{@$backlink_profile_summary->referringLinks}}</td>
                </tr>
                <tr>
                  <td>NoFollow Links</td>
                  <td>{{@$backlink_profile_summary->noFollowLinks}}</td>
                </tr>
                <tr>
                  <td>DoFollow Links</td>
                  <td>{{@$backlink_profile_summary->doFollowLinks}}</td>
                </tr>
                <tr>
                  <td>Facebook Links</td>
                  <td>{{@$backlink_profile_summary->facebookLinks}}</td>
                </tr>
                <tr>
                  <td>PInterest Links</td>
                  <td>{{@$backlink_profile_summary->pinterestLinks}}</td>
                </tr>
                <tr>
                  <td>LinkedIn Links</td>
                  <td>{{@$backlink_profile_summary->linkedinLinks}}</td>
                </tr>
                <tr>
                  <td>VK Links</td>
                  <td>{{@$backlink_profile_summary->vkLinks}}</td>
                </tr>
                <tr>
                  <td>Type Text</td>
                  <td>{{@$backlink_profile_summary->typeText}}</td>
                </tr>
                <tr>
                  <td>Type Img</td>
                  <td>{{@$backlink_profile_summary->typeImg}}</td>
                </tr>
                <tr>
                  <td>Type Redirect</td>
                  <td>{{@$backlink_profile_summary->typeRedirect}}</td>
                </tr>
                @else
                <tr>
                  <td>Backlinks</td>
                  <td>{{@$backlink_profile_summary->total?:0}}</td>
                </tr>
                <tr>
                  <td>Referring Domains</td>
                  <td>{{@$backlink_profile_summary->domains_num?:0}}</td>
                </tr>
                <tr>
                  <td>Referring Urls</td>
                  <td>{{@$backlink_profile_summary->urls_num?:0}}</td>
                </tr>
                <tr>
                  <td>Referring IPs</td>
                  <td>{{@$backlink_profile_summary->ips_num?:0}}</td>
                </tr>
                <tr>
                  <td>DoFollow Links</td>
                  <td>{{@$backlink_profile_summary->follows_num?:0}}</td>
                </tr>
                <tr>
                  <td>NoFollow Links</td>
                  <td>{{@$backlink_profile_summary->nofollows_num?:0}}</td>
                </tr>
                <tr>
                  <td>Sponsored</td>
                  <td>{{@$backlink_profile_summary->sponsored_num?:0}}</td>
                </tr>
                <tr>
                  <td>Ugc</td>
                  <td>{{@$backlink_profile_summary->ugc_num?:0}}</td>
                </tr>
                <tr>
                  <td>Texts</td>
                  <td>{{@$backlink_profile_summary->texts_num?:0}}</td>
                </tr>
                <tr>
                  <td>Images</td>
                  <td>{{@$backlink_profile_summary->images_num?:0}}</td>
                </tr>
                @endif

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="project-table-cover">
      <div class="project-table-head">
        <div class="project-entries">
          <label>Show
            <select id="backlink_limit">
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
          entries</label>
        </div>

        <div class="project-search">
          <form>
            <input type="text" placeholder="Search..." class="backlink_search" onkeydown="return (event.keyCode!=13);">
            <div class="refresh-search-icon" id="refresh-backlinks-search">
                <span uk-icon="refresh"></span>
            </div>
            <a href="javascript:;" class="backLink-search-clear"><span class="clear-input backLinkClear" uk-icon="icon: close;"></span></a>
            <button type="submit" onclick="return false;"><span uk-icon="icon: search"></span></button>
          </form>
        </div>
      </div>

      <div class="project-table-body backLinkTable">
        <table id="backlink_data">
          <thead>
            <tr>
              <th class="backlink_sorting ajax-loader" data-sorting_type="asc" data-column_name="url_from">
                <span uk-icon="arrow-up"></span>
                <span uk-icon="arrow-down"></span>
                Source Page Title & Url | Target Page
              </th>
              <th class="backlink_sorting ajax-loader" data-sorting_type="asc" data-column_name="link_type">
                <span uk-icon="arrow-up"></span>
                <span uk-icon="arrow-down"></span>
                Link Type
              </th>
              <th class="backlink_sorting ajax-loader" data-sorting_type="asc" data-column_name="url_to">
                <span uk-icon="arrow-up"></span>
                <span uk-icon="arrow-down"></span>
                Anchor Text
              </th>
              <th class="backlink_sorting ajax-loader" data-sorting_type="asc" data-column_name="links_ext">
                <span uk-icon="arrow-up"></span>
                <span uk-icon="arrow-down"></span>
                External Links
              </th>
              <th class="backlink_sorting ajax-loader" data-sorting_type="asc" data-column_name="first_seen">
                <span uk-icon="arrow-up"></span>
                <span uk-icon="arrow-down"></span>
                First Seen
              </th>
              <th class="backlink_sorting ajax-loader" data-sorting_type="asc" data-column_name="last_visited">
                <span uk-icon="arrow-up"></span>
                <span uk-icon="arrow-down"></span>
                Last Seen
              </th>
            </tr>
          </thead>
          <tbody>
               @for($i=1; $i<=5; $i++)
                  <tr>
                     <td class="ajax-loader">....</td>
                     <td class="ajax-loader">....</td>
                     <td class="ajax-loader">....</td>
                     <td class="ajax-loader">....</td>
                     <td class="ajax-loader">....</td>
                     <td class="ajax-loader">....</td>
                  </tr>   
                @endfor  
          </tbody>
        </table>
      </div>

     

      <input type="hidden" name="hidden_page_backlink" id="hidden_page_backlink" value="1" />
      <input type="hidden" name="hidden_column_name_backlink" id="hidden_column_name_backlink" value="first_seen" />
      <input type="hidden" name="hidden_sort_type_backlink" id="hidden_sort_type_backlink" value="desc" />
      <input type="hidden" name="limit_backlink" id="limit_backlink" value="100" />

      <div class="project-table-foot backlink-profile-foot">
        

      </div>

    </div>

  </div>

</div>
            <!-- Backlink Profile Row End -->
</div>  