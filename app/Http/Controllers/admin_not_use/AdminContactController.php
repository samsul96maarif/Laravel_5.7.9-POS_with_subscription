<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Contact;

class AdminContactController extends Controller
{
  public function index()
  {
    $contacts = contact::all();
    return view('admin/contact/index', ['contacs' => $contacts]);
  }

  public function show($id)
  {
    $contact = contact::findOrFail($id);
    return view('admin/contact/detail', ['contact' => $contact]);
  }
}
