<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;

class SearchController extends Controller
{
    
    public function show()
    {
        return view('pages.search.show');
    }
    
    public function users(Request $request)
    {
        $usersBuilder = User::with('details');
        if ($request->query->has('keywords')) {
            $keywords = $request->query->get('keywords');
            $usersBuilder->whereHas('details', function(Builder $builder) use ($keywords) {
                $builder->where('display_name', 'like', '%' . $keywords . '%');
            });
        } else {
            $usersBuilder->limit(12)->inRandomOrder();
        }
        
        return $usersBuilder->get();
    }
}
