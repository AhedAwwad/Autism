@extends('layouts.app')
@section('css')
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="../form/css/style.css" rel='stylesheet' type='text/css' />
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
@endsection
@section('js')
<script src="/js/filter.js"></script>

@endsection
@section('content')

    <section class="hero-area bg-img bg-overlay-2by5" style="background-image: url(/img/bg-img/3z.jpg);">
        
    </section>
    <!-- ##### Hero Area End ##### -->

    <!-- ##### Cool Facts Area Start ##### -->
    <section class="cool-facts-area section-padding-100-0">
        <div class="container">
            <div class="row">
                <!-- Single Cool Facts Area -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="single-cool-facts-area text-center mb-100 wow fadeInUp" data-wow-delay="250ms">
                        <div class="icon">
                            <img src="/img/core-img/5.png" alt="">
                        </div>
                        <h2><a style="text-decoration: none;color: #1dc8d9;" href="">تحميل</a></h2>
                        <h5>نسخة الأندرويد</h5>
                    </div>
                </div>

                <!-- Single Cool Facts Area -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="single-cool-facts-area text-center mb-100 wow fadeInUp" data-wow-delay="500ms">
                        <div class="icon">
                            <img src="/img/core-img/2.png" alt="">
                        </div>
                        <h2><span class="counter" style="color: #1dc8d9;">{{$count_message}}</span></h2>
                        <h5>رسالة</h5>
                    </div>
                </div>

                <!-- Single Cool Facts Area -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="single-cool-facts-area text-center mb-100 wow fadeInUp" data-wow-delay="750ms">
                        <div class="icon">
                            <img src="/img/core-img/3.png" alt="">
                        </div>
                        <h2><span class="counter" style="color: #1dc8d9;">89</span></h2>
                        <h5>عملية بحث </h5>
                    </div>
                </div>

                <!-- Single Cool Facts Area -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="single-cool-facts-area text-center mb-100 wow fadeInUp" data-wow-delay="1000ms">
                        <div class="icon">
                            <img src="/img/core-img/4.png" alt="">
                        </div>
                        <h2><span class="counter" style="color: #1dc8d9;">{{$count_child}}</span></h2>
                        <h5>طفل و أكثر </h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ##### Cool Facts Area End ##### -->

    <!-- ##### Popular Courses Start ##### -->
    <section class="popular-courses-area section-padding-100-0" style="padding-top: 0px; background-image: url(/img/core-img/texture.png);">
        <div class="container">
          
<h3 style="color: #AED6F1;font-family: monospace;"><b><i>أنا مش مريض</i></b></h3>
<h3 style="color: #AED6F1;font-family: monospace;"><b><i>أنا عندي اضطراب توحد</i></b></h3>

<video style="border-color: red;border-bottom-left-radius: 30%;
" width="700"  controls>
  <source src="/img/core-img/ved.mp4" type="video/mp4">
  
  
</video>
                    </div>
        </div>
    </section>
    <!-- ##### Popular Courses End ##### -->

    <!-- ##### Best Tutors Start ##### -->
    <section class="best-tutors-area section-padding-100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading">
                       <h3 style="color: #AED6F1;font-family: monospace;"><b><i>مراحل برنامج بيكس</i></b></h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="tutors-slide owl-carousel wow fadeInUp" data-wow-delay="250ms">

                        <!-- Single Tutors Slide -->
                        <div class="single-tutors-slides">
                            <!-- Tutor Thumbnail -->
                            <div class="tutor-thumbnail">
                                <img style="height: 167px;" src="/img/bg-img/1ss.png" alt="">
                            </div>
                            <!-- Tutor Information -->
                            <div class="tutor-information text-center">
                                <h5>المرحلة الأولى</h5>
                                <span>التبادل بالمساعدة الجسدية</span>
                                <p>يتطلب ذلك وجود شخصين أحدهما يكون بجانب الطفل أو خلفه، والآخر يكون أمامه: ويكون هناك معزز للطفل ويفترض تعرف أشياء محببة للطفل وتوضع بطاقة مكتوب عليها الشيء المرغوب فيه والذي يريد الطفل الوصول إليه فيساعده الشخص الذي خلف الطفل في الحصول على البطاقة والذهاب إلى الشخص الذي يكون أمامه وتكون يده مفتوحة لاستلام البطاقة ويعطيه الشيء المرغوب فيه بعد أن يمدحه على ذلك فالشخص الأول يساعد جسدياً للحصول على البطاقة والوصول إلى المدرب الذي أمامه والثاني يستخدم طريقة الحث كمناداة الطفل باسمه ويذكره بأن الشيء المرغوب فيه موجود لديه</p>
                            </div>
                        </div>

                        <!-- Single Tutors Slide -->
                        <div class="single-tutors-slides">
                            <!-- Tutor Thumbnail -->
                            <div class="tutor-thumbnail">
                                <img style="height: 167px;" src="/img/bg-img/2s.png" alt="">
                            </div>
                            <!-- Tutor Information -->
                            <div class="tutor-information text-center">
                                <h5>المرحلة الثانية</h5>
                                <span>توسيع مفهوم التنقل التلقائي</span>
                                <p>وفي هذه المرحلة يذهب الطفل إلى لوحة الاتصال، ويرفع الصورة التي تمثل الشيء المرغوب فيه ويذهب ليضعها في يد المدرب، ويمكن أن تزيد المسافة تدريجياً بين الطفل والمدرب.</p>
                            </div>
                        </div>

                        <!-- Single Tutors Slide -->
                        <div class="single-tutors-slides">
                            <!-- Tutor Thumbnail -->
                            <div class="tutor-thumbnail">
                                <img style="height: 167px;" src="/img/bg-img/3sss.png" alt="">
                            </div>
                            <!-- Tutor Information -->
                            <div class="tutor-information text-center">
                                <h5>المرحلة الثالثة</h5>
                                <span>تمييز الصور</span>
                                <p>وفي هذه المرحلة يختار الطفل صورة من بين عدة صور وينزعها من لوحة الاتصال ويذهب ويضعها في يد المدرب، ويمكن أن يواجه المدرب الطفل على الطاولة نفسها ويضع صوراً متعددة لها علاقة بالأشياء المرغوب فيها، وبأشياء غير مرغوب فيها، وفي هذه المرحلة لا يفضل استخدام الحث اللفظي، ويغيّر ترتيب الصور حتى يتم التأكد من قدرة الطفل على التمييز</p>
                            </div>
                        </div>

                        <!-- Single Tutors Slide -->
                        <div class="single-tutors-slides">
                            <!-- Tutor Thumbnail -->
                            <div class="tutor-thumbnail">
                                <img style="height: 167px;" src="/img/bg-img/4sss.png" alt="">
                            </div>
                            <!-- Tutor Information -->
                            <div class="tutor-information text-center">
                                <h5>المرحلة الرابعة</h5>
                                <span>تركيب الجملة</span>
                                <p>حيث يطلب من الطفل التوحدي استخدام كلمات متعددة لطلب أشياء، حيث يلتقط الطفل صورة أو رمزاً (أنا أريد) (أنا بدّي) ويضعها على شريط الجملة، ثم ينزع صورة ما يرغب فيها ويضعها على شريط الجملة ليكون جملة ذات معنى، توضع الصورة (أنا أريد) على الجانب الأيمن، ثم توضع صورة الشيء المرغوب فيه إلى يسار الصورة لتشكل جملة، أنا أريد ماء. ويتم استخدام الحث والتلاشي.</p>
                            </div>
                        </div>

                        <!-- Single Tutors Slide -->
                        <div class="single-tutors-slides">
                            <!-- Tutor Thumbnail -->
                            <div class="tutor-thumbnail">
                                <img style="height: 167px;" src="/img/bg-img/5ss.png" alt="">
                            </div>
                            <!-- Tutor Information -->
                            <div class="tutor-information text-center">
                                <h5>المرحلة الخامسة</h5>
                                <span>الاستجابة لطلب</span>
                                <p>وفي هذه المرحلة يمكن للطفل التوحدي أن يكون قادراً على طلب أشياء بصورة تلقائية، وأن يجيب عن سؤال ماذا تريد؟ مستخدماً التعزيز الاجتماعي والمادي لكل استجابة صحيحة.</p>
                            </div>
                        </div>

                        <div class="single-tutors-slides">
                            <!-- Tutor Thumbnail -->
                            <div class="tutor-thumbnail">
                                <img  style="height: 167px;" src="/img/bg-img/66.png" alt="">
                            </div>
                            <!-- Tutor Information -->
                            <div class="tutor-information text-center">
                                <h5>المرحلة السادسة</h5>
                                <span>التجاوب والردود التلقائية</span>
                                <p>وفي هذه المرحلة يجيب الطفل بطريقة مناسبة عن: ماذا تريد؟ ماذا ترى؟ ماذا عندك؟ وأسئلة مشابهة.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ##### Best Tutors End ##### -->

    @endsection