@extends('public.layouts.master')

@section('title', 'Contact')

@section('content')
    <br><br>
    <br><br>
    <br><br>
    <br><br>
    <!-- Contact -->
    <div class="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">

                    <!-- Contact Form -->
                    <div class="contact_form">
                        <div class="contact_title">Get in touch</div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="contact_form_container">
                            <form action="{{ route('public.sendFeedback') }}" method="POST">
                                @csrf
                                <input id="contact_form_name" class="input_field contact_form_name" type="text" placeholder="Name" name="name">
                                <input id="contact_form_email" class="input_field contact_form_email" type="email" placeholder="E-mail" name="email">
                                <br><br>
                                <textarea id="contact_form_message" class="text_field contact_form_message" name="message" placeholder="Message"></textarea>
                                <button id="contact_send_btn" type="submit" class="contact_send_btn trans_200">send message</button>
                            </form>
                        </div>
                    </div>

                </div>

                <div class="offset-lg-1 col-lg-4">
                    <div class="about">
                        <div class="about_title">Information </div>
                        <p class="about_text">
                            The National Polytechnic Institute of Cambodia (NPIC) is a standard Institute of Technology in Cambodia which is recognized by the Government and the Republic of Korea. Officially collaborating with Jeon Ju University, South Korea. The National Polytechnic of Cambodia (NPIC) was officially opened on 16 May 2005 by Prime Minister Hun Sen.
                        </p>

                        <div class="contact_info">
                            <ul>
                                <li class="contact_info_item">
                                    <div class="contact_info_icon">
                                        <img src="{{ asset('img/placeholder.svg') }}" alt="https://www.flaticon.com/authors/lucy-g">
                                    </div>
                                    Phum Prey Popel, SK. Somrong Krom, Khan Po Sen Chey, (Near Wat Pun Phnom), Phnom Penh 12000
                                </li>
                                <li class="contact_info_item">
                                    <div class="contact_info_icon">
                                        <img src="{{ asset('img/smartphone.svg') }}" alt="https://www.flaticon.com/authors/lucy-g">
                                    </div>
                                    (+855)-23-691-5193
                                </li>
                                <li class="contact_info_item">
                                    <div class="contact_info_icon">
                                        <img src="{{ asset('img/envelope.svg') }}" alt="https://www.flaticon.com/authors/lucy-g">
                                    </div>
                                    npicinfo@gmail.com
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
