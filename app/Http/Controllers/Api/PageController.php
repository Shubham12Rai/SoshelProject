<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Models\Option;
use App\Models\Faqs;

class PageController extends ApiHelper
{
	/**
     * To get terms-and-conditions/faq/privacy-policy data
     * @param req :[]
     * @return res : [Get data as per passed url (terms-and-conditions/faq/privacy-policy)]
     */
	public function __invoke($page)
	{
		if ($page === config('constants.STATIC_CONTENT.Term&Conditions')) {
			$termsAndCoditionTitle = config('constants.STATIC_CONTENT.TermConditionsWhereClause');
			$termsAndCodition = Option::where('option_title', $termsAndCoditionTitle)->first();

			$response = [
				'option_title' => $termsAndCodition->option_title,
				'option_detail' => $termsAndCodition->option_detail
			];
			return $this->successRespond($response, 'StaticContentData');

		} elseif ($page === config('constants.STATIC_CONTENT.privacyPolicy')) {
			$privacyPolicyTitle = config('constants.STATIC_CONTENT.privacyPolicyWhereClause');
			$termsAndCodition = Option::where('option_title', $privacyPolicyTitle)->first();

			$response = [
				'option_title' => $termsAndCodition->option_title,
				'option_detail' => $termsAndCodition->option_detail
			];
			return $this->successRespond($response, 'StaticContentData');

		} elseif ($page === config('constants.STATIC_CONTENT.faqs')) {
			$faqs = Faqs::all();

			return $this->successRespond($faqs, 'FaqsData');

		} else {
			return $this->errorRespond("InvalidPage", config('constants.CODE.notFound'));
		}
	}
}