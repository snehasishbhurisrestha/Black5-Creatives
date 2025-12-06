@extends('layouts.web-app')

@section('title') Contact Us @endsection

@section('content')

<div class="map">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d834.1784362001428!2d88.40640186954944!3d22.459541638104938!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a02722f2f7c6991%3A0x71ae6da1f86fec18!2sBoalia%20Main%20Rd%2C%20Naskar%20Para%2C%20Gajeepur%20Para%2C%20Garia%2C%20Rajpur%20Sonarpur%2C%20West%20Bengal%20700152!5e1!3m2!1sen!2sin!4v1747244682271!5m2!1sen!2sin" height="500" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
</div>
<!-- Contact Page -->
{{-- <div class="contact_page_area fix">
    <div class="container">					
        <div class="row">
            <div class="contact_frm_area text-left col-lg-6 col-md-12 col-xs-12">
                <h3>Get in Touch</h3>
                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-sm-6"><input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Name*" /></div>
                        <div class="form-group col-sm-6"><input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email*" /></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-sm-6"><input type="tel" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="Phone*" /></div>
                        <div class="form-group col-sm-6"><input type="text" class="form-control" name="subject" value="{{ old('subject') }}" placeholder="Subject" /></div>
                    </div>
        
                    <div class="form-group">
                        <textarea name="message" class="form-control" name="message" placeholder="Message">{{ old('message') }}</textarea>
                    </div>
                    
                    <div class="input-area submit-area"><button class="btn border-btn" type="submit" >Send Message</button></div>
                    
                </form>		
            </div>	
        
            <div class="contact_info col-lg-6 col-md-12 col-xs-12">
                <h3>Contact Info</h3>
                <p class="subtitle">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s
                </p>
                <div class="single_info">
                    <div class="con_icon"><i class="fa fa-map-marker"></i></div>
                    <p>Kavi Nazrul Sarani, Opposite Asma Dhaba, </br>
                        Baruipur, Kolkata, India, 700144</p>
                </div>
                <div class="single_info">
                    <div class="con_icon"><i class="fa fa-phone"></i></div>
                    <p>Phone : +91 70036 96900</p>
                </div>
                <div class="single_info">
                    <div class="con_icon"><i class="fa fa-envelope"></i></div>
                    <a href="#">gsmmusicalkolkata@hotmail.com</a>
                </div>
                
            </div>
        </div>
    </div>

                    
    <div class="fix">
        <div id="contact_map_area">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3689.7115452661424!2d88.42513477434663!3d22.364518040566473!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a026ddf8a344981%3A0xc845c1e9b59dbf30!2sGSM%20Musical!5e0!3m2!1sen!2sin!4v1738389699104!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>	
        
</div> --}}
<section class="contact spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="contact-text">
                    <div class="section-title">
                        <span>Information</span>
                        <h2>Contact Us</h2>
                        <p>Have a question, a style tip to share, or need help with your order? We’re here for every stitch, shade, and size. Reach out — fashion is better when we connect.</p>
                    </div>
                    <ul>
                        <!-- <li>
                            <h4>America</h4>
                            <p>195 E Parker Square Dr, Parker, CO 801 <br />+43 982-314-0958</p>
                        </li>
                        <li>
                            <h4>France</h4>
                            <p>109 Avenue Léon, 63 Clermont-Ferrand <br />+12 345-423-9893</p>
                        </li>
                    </ul> -->
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="contact-form">
                    {{-- <form action="#">
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="text" placeholder="Name">
                            </div>
                            <div class="col-lg-6">
                                <input type="text" placeholder="Email">
                            </div>
                            <div class="col-lg-12">
                                <textarea placeholder="Message"></textarea>
                                <button type="submit" class="site-btn">Send Message</button>
                            </div>
                        </div>
                    </form> --}}
                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-sm-6"><input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Name*" /></div>
                            <div class="form-group col-sm-6"><input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email*" /></div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-sm-6"><input type="tel" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="Phone*" /></div>
                            <div class="form-group col-sm-6"><input type="text" class="form-control" name="subject" value="{{ old('subject') }}" placeholder="Subject" /></div>
                        </div>
            
                        <div class="form-group">
                            <textarea name="message" class="form-control" name="message" placeholder="Message">{{ old('message') }}</textarea>
                        </div>
                        
                        {{-- <div class="input-area submit-area"><button class="btn border-btn" type="submit" >Send Message</button></div> --}}
                        <button type="submit" class="site-btn">Send Message</button>
                        
                    </form>	
                </div>
            </div>
        </div>
    </div>
</section>
@endsection