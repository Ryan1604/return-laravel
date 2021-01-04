@extends('public.layouts.master')

@section('title', 'About Us')

@section('content')
    <br><br>
    <br><br>
    <br><br>
    <!-- News -->
    <div class="news">
        <div class="container">
            <div class="row">
                <!-- News Post Column -->
                <div class="col-lg-12">
                    <div class="news_post_container">
                        <!-- News Post -->
                        <div class="news_post">
                            <br> <br> <br>
                            <div class="news_post_image">
                                <img src="{{ asset('img/npic-1.jpg') }}" alt="https://unsplash.com/@dsmacinnes">
                            </div>
                            <div class="news_post_top d-flex flex-column flex-sm-row">
                                <div class="news_post_date_container">
                                </div>
                                <br> <br>
                            </div>
                            <h1>About NPIC Library</h1>
                            <div class="news_post_text">
                                <p>
                                    The National Polytechnic Institute of Cambodia (NPIC) is a standard Institute of Technology in Cambodia which is recognized by the Government and the Republic of Korea. Officially collaborating with Jeon Ju University, South Korea. The National Polytechnic of Cambodia (NPIC) was officially opened on 16 May 2005 by Prime Minister Hun Sen.
                                    The library is a place to study, find and develop information or educational institutions, and also as an educational tool in education that is managed in such away. The library contains a collection of books either in printed form or digital books that can be accessed by computer networks.
                                </p>
                            </div>

                            <div class="news_post_quote">
                                <p class="news_post_quote_text">
                                    In general, the library has several general functions, namely: 1) information function, 2) education function, 3) cultural function, 4) recreation function, 5) research function, and 6) deposit function.
                                </p>
                            </div>

                            <p class="news_post_text" style="margin-top: 59px;">
                                This system provides an advantage for libraries on campus especially for the National Polytechnic Institute of Cambodia (NPIC) Library because all books can be accessed on the website so that NPIC librarians can see how many books have been borrowed, books are missing, book stock, e-books and members can view books by category, newest, popular and member can read and download e-book.
                                The result of this system is to make it easier for library staff to find data about books, loans, book returns, and other library management. Students and Lecturers can access anywhere and anytime, making it easier to manage each report.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
