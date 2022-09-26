<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeoAnalyticsEditSection extends Model
{
    protected $table = 'seo_analytics_edit_section';
    
    protected $primaryKey = 'id';
    
    protected $fillable = ['user_id', 'request_id', 'edit_section', 'display'];


   public static function summary_section($campaign_id,$user_id){
		$data = SeoAnalyticsEditSection::
				where('user_id',$user_id)
				->where('request_id',$campaign_id)
				->first();

		return $data;
	}

	public static function CustomNote($user_id, $last_inserted_id) {
		SeoAnalyticsEditSection::create([
			'user_id' => $user_id,
			'request_id' => $last_inserted_id,
			'edit_section' => "<b>Welcome to Your Dashboard!</b>
			<p>This dashboard gives you an at-a-glance view of the aspects of your campaign that are most important to you. And since it's customizable, you can ask your account manager to update it for you.</p>
			<h5>This dashboard shows you: </h5>
			<ul>
			<li>a. Traffic from Google analytics. </li>
			<li>b. Visibility of your campaign in Google from Search Console. </li>
			<li>c. Additional Keywords that you are ranking for from SEMRUSH.</li> 
			<li>d. Work performed by our team in Activity Section and much more.</li></ul>
			<p>You can download a PDF copy of whole report by clicking a button in top right section.</p>"
		]);
		return true;
	}
}
