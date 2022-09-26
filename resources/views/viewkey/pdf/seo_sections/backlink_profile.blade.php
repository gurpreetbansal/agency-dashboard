 <!-- Backlink Profile Row -->
 <div class="white-box pa-0 mb-40">
  <div class="box-boxshadow">
   <div class="section-head">
     <h4>
       <figure><img src="{{URL::asset('public/vendor/internal-pages/images/backlink-profile-img.png')}}"></figure>
       Backlink Profile
       <font class="backlink_profile_time"></font>
     </h4>
     <hr />
     <p>
       <small><em><img src="{{URL::asset('public/vendor/internal-pages/images/info.png')}}"> Overview of referring domains of the website</em></small>
     </p>
     <p>This graph shows how the number of domain backlinks has changed. The info on the graph is updated once a week only when there is new data.</p>
   </div>
   <div class="white-box-body">
     <div class="chart h-230 ma-space">
       <input type="hidden" class="backlinkSelectdChart" value="all">
       <canvas id="chart-referring-domains"></canvas>
     </div>
     <div class="top-organic-keyword-table mt-40">
       <div class="section-head">
         <h4>Summary</h4>
         <hr />
         <p>The general information about link profile: referring domains and subdomains, referring IP's,
           referring links,follow/unfollow links, Social Media links, Number of Text Backlinks, Image
         Bcklinks, Referring redirects. </p>
       </div>
       <div class="grid-listing uk-flex">
        @if($flag == 1)
        <div class="box-boxshadow">
          <table>
            <tbody>
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
            </tbody>
          </table>
        </div>
        <div class="box-boxshadow">
          <table>
            <tbody>
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
            </tbody>
          </table>
        </div>
        @else
        <div class="box-boxshadow">
          <table>
            <tbody>
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
            </tbody>
          </table>
        </div>
        <div class="box-boxshadow">
          <table>
            <tbody>
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
            </tbody>
          </table>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<div class="box-boxshadow BreakBefore">
  <div class="project-table-cover">
   <div class="section-head">
     <h4>
       <figure><img src="{{URL::asset('public/vendor/internal-pages/images/backlink-profile-img.png')}}">
       </figure>
       New Backlinks
     </h4>
     <hr />
     <p>
       <small><em><img src="{{URL::asset('public/vendor/internal-pages/images/info.png')}}"> Most recent
       backlinks discovered for the domain</em></small>
     </p>
   </div>
   <div class="project-table-body backLinkTable">
     <div class="table-box">
       <table id="backlink_data">
         <thead>
           <tr>
             <th class="backlink_sorting" data-sorting_type="asc" data-column_name="url_from">
               <span uk-icon="arrow-up"></span>
               <span uk-icon="arrow-down"></span>
               Source Page Title & Url | Target Page
             </th>
             <th class="backlink_sorting" data-sorting_type="asc" data-column_name="link_type">
               <span uk-icon="arrow-up"></span>
               <span uk-icon="arrow-down"></span>
               Link Type
             </th>
             <th class="backlink_sorting" data-sorting_type="asc" data-column_name="url_to">
               <span uk-icon="arrow-up"></span>
               <span uk-icon="arrow-down"></span>
               Anchor Text
             </th>
             <th class="backlink_sorting" data-sorting_type="asc" data-column_name="links_ext">
               <span uk-icon="arrow-up"></span>
               <span uk-icon="arrow-down"></span>
               External Links
             </th>
             <th class="backlink_sorting" data-sorting_type="asc" data-column_name="first_seen">
               <span uk-icon="arrow-up"></span>
               <span uk-icon="arrow-down"></span>
               First Seen
             </th>
             <th class="backlink_sorting" data-sorting_type="asc" data-column_name="last_visited">
               <span uk-icon="arrow-up"></span>
               <span uk-icon="arrow-down"></span>
               Last Seen
             </th>
           </tr>
         </thead>
         <tbody></tbody>
       </table>
     </div>
   </div>
   <input type="hidden" name="hidden_page_backlink" id="hidden_page_backlink" value="1" />
   <input type="hidden" name="hidden_column_name_backlink" id="hidden_column_name_backlink" value="first_seen" />
   <input type="hidden" name="hidden_sort_type_backlink" id="hidden_sort_type_backlink" value="desc" />
   <input type="hidden" name="limit_backlink" id="limit_backlink" value="7" />
 </div>
 <div class="uk-text-center pa-20">
   <p class="mb-0">
    <a href="{{url('/project-detail/'.$share_key)}}" target="_blank" class="btn blue-btn">To view more
     Click here <i class="fa fa-external-link"></i></a>
   </p>
 </div>
</div>
</div>
 <!-- Backlink Profile Row End -->