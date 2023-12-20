<?php

namespace App\Repositories;

use App\Repositories\Interfaces\ChatbotRepositoryInterface;
use App\Models\Faq;
use Illuminate\Support\Facades\Hash;

class ChatbotRepository implements ChatbotRepositoryInterface
{
    public function allFAQ()
    {
        return Faq::where('deleted_at', 0)
        ->get();
    }

    public function storeFAQ($data)
    {
        return Faq::create($data);
    }

    public function findFAQ($id)
    {
        return Faq::find($id);
    }

    public function updateFAQ($data, $id)
    {
        $faq = Faq::where('id', $id)->first();
        $faq->question = $data['question'];
        $faq->answer = $data['answer'];
        $faq->save();
    }

    public function destroyFAQ($id)
    {
        $faq = Faq::find($id);
        $faq->deleted_at = 1;
        $faq->save();
    }
}
