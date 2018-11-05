<?php

namespace Wink\Http\Controllers;

class SPAViewController
{
    /**
     * Single page application catch-all route.
     *
     * @return \Illuminate\Http\Response.
     */
    public function __invoke()
    {
        return view('wink::layout');
    }
}
