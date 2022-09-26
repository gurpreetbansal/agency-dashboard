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