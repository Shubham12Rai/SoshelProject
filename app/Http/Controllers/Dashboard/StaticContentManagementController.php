<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;
use App\Models\Faqs;
use Illuminate\Support\Facades\Validator;

class StaticContentManagementController extends Controller
{
    public function staticManagementLoad()
    {
        $privacyPolicy = Option::Where('option_title', '=', 'Privacy Policy')->get();
        return view('auth.dashboard.staticContentManagement', compact('privacyPolicy'));
    }
    public function privacyPolicyUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $option = Option::find($id);
        $option->option_detail = $request['description'];
        $option->updated_at = now();
        $option->save();

        return redirect()->back()->with('statusPrivacy', 'Privacy Policy Update Sucessfully');
    }
    public function termConditionView()
    {
        $termCondition = Option::Where('option_title', '=', 'Term & Conditions')->get();
        return view('auth.dashboard.termConditionView', compact('termCondition'));
    }
    public function termConditionUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $option = Option::find($id);
        $option->option_detail = $request['description'];
        $option->updated_at = now();
        $option->save();

        return redirect()->back()->with('statusTermCondition', 'Term Condition Update Sucessfully');
    }
    public function faqView()
    {
        $faq = Faqs::all();
        return view('auth.dashboard.faqView', compact('faq'));
    }
    public function faqAdd()
    {
        return view('auth.dashboard.faqAdd');
    }
    public function faqUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question.*' => 'required|string',
            'answer.*' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $question = $request['question'];
        $answer = $request['answer'];
        $count = count($question);

        for ($i = 0; $i < $count; $i++) {
            $faq = new Faqs;
            $faq->question = $question[$i];
            $faq->answer = $answer[$i];
            $faq->created_at = now();
            $faq->updated_at = now();
            $faq->save();
        }

        return redirect()->back()->with('statusFaqAdd', 'Term Condition Update Sucessfully');

    }
    public function faqEdit($id)
    {
        $faq = Faqs::Where('id', $id)->first();

        return view('auth.dashboard.faqEdit', compact('faq'));
    }
    public function faqEditUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'answer' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $faq = Faqs::find($id);
        $faq->question = $request['question'];
        $faq->answer = $request['answer'];
        $faq->updated_at = now();
        $faq->save();

        return redirect()->back()->with('statusFaqEdit', 'FAQs Update Sucessfully');

    }
    public function faqDelete($id)
    {
        $faq = Faqs::Where('id', '=', $id);
        $faq->delete();
        $faq = Faqs::all();
        return view('auth.dashboard.faqView', compact('faq'));

    }
}