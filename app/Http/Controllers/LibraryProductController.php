<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\LibraryProductServiceInterface;
use App\Services\Interfaces\MemberCardServiceInterface;
use App\Services\Interfaces\MemberSubscribeProductServiceInterface;
use Illuminate\Http\Request;

class LibraryProductController extends Controller
{
    public function __construct(
        protected LibraryProductServiceInterface $productService,
        protected MemberCardServiceInterface $cardService,
        protected MemberSubscribeProductServiceInterface $service
    ) {}

    public function index(Request $request)
    {

    }
}
