function openMenuMobile() {
    $(".menu-mb").width("250px");
    $(".btn-menu-mb").hide("slow");
}

function closeMenuMobile() {
    $(".menu-mb").width(0);
    $(".btn-menu-mb").show("slow");
}

// load hết tất cả thằng html thì thằng code bên trong mới chạy
$(function(){


    // Submit form liên hệ
    $("form.form-contact").submit(function(event) {
        /* Act on the event */
        event.preventDefault(); //prevent default action, ngăn chặn load lại trang
        var post_url = $(this).attr("action"); //get form action url
        var request_method = $(this).attr("method"); //get form GET/POST method
        var form_data = $(this).serialize(); //Encode form elements for submission,thay vì viết dạng Object để lấy thông tin từng thằng thì lấy $(this).serialize() thì nó sẽ lấy hết cho mình luôn
        $(".message").html('Hệ thống đang gởi email... Vui lòng chờ <i class="fas fa-sync fa-spin"></i>');
        $(".message").removeClass("hidden");
        $("button[type=submit]").attr("disabled", "disabled"); // thêm 1 thuộc tính disable có giá trị là disable, tức nghĩa là làm cho cái nút đó nó sẽ ko thể bấm được
        $.ajax({
            url: post_url,
            type: request_method,
            data: form_data
        })
        .done(function(data) {
            $(".message").html(data);
            $("button[type=submit]").removeAttr("disabled"); // khi hiễn thị dữ liệu rồi thì remove cái thuộc tính disable đi 
        });
    });

       // Thay đổi province
       $("main .province").change(function(event) { // thành phố bị thay đổi
        /* Act on the event */
        var province_id = $(this).val(); 
        if (!province_id) { // trường hợp ko chọn thành phố nào hết 
            updateSelectBox(null, "main .district"); // thì cho Quận/huyện về null 
            updateSelectBox(null, "main .ward"); // phường/xã về null
            // return;
        }
        $.ajax({
            // index.php?c=address&a=getDistricts&province_id=??
            url: 'index.php?c=address&a=getDistricts',
            type: 'GET',
            data: {province_id: province_id}
        })
        .done(function(data) {
            updateSelectBox(data, "main .district"); // truyền zô cho thằng quận/huyện 
            updateSelectBox(null, "main .ward"); // phường/xã vẫn null
        });

        if ($("main .shipping-fee").length) {
            $.ajax({
                url: 'index.php?c=address&a=getShippingFee',
                type: 'GET',
                data: {province_id: province_id}
            })
            .done(function(data) {
                //update shipping fee and total on UI
                let shipping_fee = Number(data);
                let payment_total = Number($("main .payment-total").attr("data")) + shipping_fee;

                $("main .shipping-fee").html(number_format(shipping_fee) + "₫"); 
                $("main .payment-total").html(number_format(payment_total) + "₫");
            });
        }

        
    });

    // Thay đổi district, thì nó mới cập nhật cho thằng phường/xã
    $("main .district").change(function(event) { 
        /* Act on the event */
        var district_id = $(this).val();
        if (!district_id) {
            updateSelectBox(null, "main .ward");
            return;
        }

        $.ajax({
            url: 'index.php?c=address&a=getWards',
            type: 'GET',
            data: {district_id: district_id}
        })
        .done(function(data) {
            updateSelectBox(data, "main .ward");
        });
    });


    // nút đặt hàng
    $('input[name=checkout]').click(function(event) {
        /* Act on the event */
        window.location.href="index.php?c=payment&a=checkout";
    });
    // Nút tiếp tục mua sắm 
    $('input[name=back-shopping]').click(function(event) {
        /* Act on the event */
        window.location.href="index.php?c=product&a=index";
    });

      // Thêm sản phẩm vào giỏ hàng
      $("main .buy-in-detail").click(function(event) {
        /* Act on the event */
        var qty = $(this).prev("input").val(); // là thẻ input chứa số lượng sp, prev : là thành phần phía trước nó
        var product_id = $(this).attr("product-id");
        $.ajax({
            url: 'index.php?c=cart&a=add',
            type: 'GET',
            data: {product_id: product_id, qty: qty}
        })
        .done(function(data) {
            displayCart(data);
            
        });
    });

    // display sp khi mới load trình duyệt 
    $.ajax({
        url: "index.php?c=cart&a=display",
        type: "GET"
    })
        .done(function(data) {
            displayCart(data);
        }
    );
     // Thêm sản phẩm vào giỏ hàng
     $("main .buy").click(function(event) {
        /* Act on the event */
        
        var product_id = $(this).attr("product-id");
        $.ajax({
            url: 'index.php?c=cart&a=add',
            type: 'GET',// làm thay đổi cái url
            data: {product_id: product_id, qty:1} // qty:1 do mỗi lần đặt thì chỉ bấm 1 lần dc 1 sp
            // khi bấm zô nó sẽ sinh ra đường dẫn 
            //http://shop.com/site/index.php?c=cart&a=add&product_id=14&qty=1, gửi data này lên server để xử lý 
            // để thu được data
            // xong rồi qua thằng CartController xem tiếp thằng server nó làm gì, là qua CartController
            // date thu được VD:
            // {"items":{"3":{"img":"kemLamSangVungDaBikini.jpg","name":"Kem l\u00e0m s\u00e1ng v\u00f9ng da bikini Beaumore- 50ml","product_id":"3","qty":3,"unit_price":"849000","total_price":2547000},"9":{"img":"suaTamSandrasMychai250ml.jpg","name":"S\u1eefa t\u1eafm Sandras M\u1ef9 chai 250ml","product_id":"9","qty":5,"unit_price":"210000","total_price":1050000}},"total_product_number":8,"total_price":3597000}
        })
        .done(function(data) {
            // console.log(data);
            displayCart(data);
        });
    });


    // validation register form 
    $(".form-register").validate({
        rules: {
          // simple rule, converted to {required:true}
          fullname:{
            required:true,
            maxlength:50,
            regex:
            /^[a-zA￾ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s]+$/i,
          },
          mobile:{
            required: true,
            regex: /^0([0-9]{9,9})$/,
          },
          email:{
            required:true,
            maxlength:50,
            email:true,// tự động bật type email là phải có @
            remote:"/site/index.php?c=register&a=notExistingEmail"// remote: dùng để check email có tồn tại chưa, false nếu tồn tại, true nếu ko tồn tại, lưu ý phải clik unique trong email
          },
          password:{
            required:true,
            regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/
          },
          password_confirmation:{
            required:true,
            equalTo:"[name=password]"
          },
         
        hiddenRecaptcha: {
            //true: lỗi
            //false: passed
            required: function () {
                if (grecaptcha.getResponse() == '') {
                    return true;
                } else {
                    return false;
                }
            }
        }
    
        }, 
        messages:{
            fullname:{
                required:"Vui lòng nhập họ và tên",
                maxlength:"Vui lòng nhập không quá 10 ký tự",
                regex:"Vui lòng nhập đúng tiếng việt"
            },
            email:{
                required:"Vui lòng nhập email",
                maxlength:"Vui lòng nhập không quá 10 ký tự",
                email:"Vui lòng nhập đúng định dạng email VD a@gmail.com",
                remote:"Email đã tồn tại"
            },
            mobile:{
                required:"Vui lòng nhập số điện thoại",
                regex:"Vui lòng nhập tối đa 9 số bắt đầu từ 0"
            },
            password:{
                required:"Vui lòng nhập mật khẩu",
                regex:"Vui lòng nhập chữ hoa, thường, số và ký tự đặt biệt có độ dài 8 ký tự"
            },
            password_confirmation:{
                required:"Vui lòng nhập mật khẩu xác nhận",
                equalTo:"Vui lòng nhập giống mật khẩu ở trên"
            },
            hiddenRecaptcha: {
                required:"Vui lòng xác nhận"
            }
        },
      });
    
      $(".form-login").validate({
        rules: {
          email:{
            required:true,
            maxlength:50,
            email:true,// tự động bật type email là phải có @
          },
          password:{
            required:true,
            regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/
          },   
        }, 
        messages:{
            email:{
                required:"Vui lòng nhập email",
                maxlength:"Vui lòng nhập không quá 10 ký tự",
                email:"Vui lòng nhập đúng định dạng email VD a@gmail.com",
            },
            password:{
                required:"Vui lòng nhập mật khẩu",
                regex:"Vui lòng nhập chữ hoa, thường, số và ký tự đặt biệt có độ dài 8 ký tự"
            },
        },
      });

    //   $(".info-account").validate({
    //     rules:{
    //         re_password:{
    //             required:true,
    //             equalTo:"[name=password]"
    //         }

    //     },
    //     messages:{
    //         re_password:{
    //             required:"vui lòng nhập lại mật khẩu mới",
    //             equalTo:"Vui lòng nhập đúng mật khẩu"
    //         }
    //     }
    //   });
    
      $.validator.addMethod(
        "regex",
        function (value, element, regexp) {
          if (regexp.constructor != RegExp) regexp = new RegExp(regexp);
          else if (regexp.global) regexp.lastIndex = 0;
          return this.optional(element) || regexp.test(value);
        },
        "Please check your input."
      );
    
    // Đánh giá 
    $('.form-comment').submit(function (e) { 
        e.preventDefault(); // ngăn chặn submit bình thường tức là ko load lại trang
        var form_data = $(this).serialize();
        $.ajax({
            type: "POST", // POST thì nó sẽ ko thay đổi cái url
            url: "index.php?c=product&a=storeComment",
            data:  form_data,
            success: function (response) {
                $('.comment-list').html(response);
                $('main .product-detail .product-description .answered-rating-input').rating({// thư viện của ngôi sao, ở dòng đánh giá của người dùng
                    min: 0,
                    max: 5,
                    step: 1,
                    size: 'md',
                    stars: "5",
                    showClear: false,
                    showCaption: false,
                    displayOnly: false,
                    hoverEnabled: true
                });
            }
        });
    });
    // Tìm kiếm và sắp xếp theo sản phẩm 
    $('#sort-select').change(function (e) { 
        var dataUrl = $(this).attr("data-url");
        var str_param = getUpdatedParam("sort",$(this).val());
        window.location.href =`${dataUrl}?${str_param}`;
        // window.location.href = "index.php?" + str_param;
    });
    // tìm kiếm theo range 
    $('main .price-range input').click(function (e) { 
        // var price_range = $(this).val();
        // // window.location.href : giống thằng header trong php 
        // // header("location:index.php?c=product&price-range=300000-500000")

        // // http://shop.com/khoang-gia/200000-300000
        // window.location.href = "index.php?c=product&price-range=" + price_range;

        // làm đường dẫn đẹp 
        var dataUrl = $(this).attr('dataUrl');
        window.location.href = dataUrl;
    });
     // Ajax search
     var timeout = null;// VD: khi mình gõ chữ "k""E""M" thì nó sẽ gửi request lên server tới 3 lần
                        // thay vì điều đó mình dùng cơ chế timeout khi người dùng ko gõ trong 500mili giây thì mình mới gửi request
     $("header form.header-form .search").keyup(function(event) { // .keyup: khi chỉ cần gõ phím là nó vào thằng này
         /* Act on the event */
         clearTimeout(timeout); // nghĩa là vừa vào thì thằng tiemout ko dc chạy
         var pattern = $(this).val();
         $(".search-result").html(""); // dữ liệu đổ về thì chứa ở đây
         timeout = setTimeout(function(){ // code trong thằng setTimeout sẽ bị delay đi 500mili giây
             if (pattern) {
                 $.ajax({
                     url: 'index.php?c=product&a=ajaxSearch',
                     type: 'GET', // GET làm thay đổi cái url
                     data: {pattern: pattern},//key:value
                 })
                 //index.php?c=product&a=ajaxSearch&pattern=pattern
                 .done(function(data) { // là thành công, cái nó trả về là dữ liệu và nó đổ  thằng vào ajaxSearch.php rồi nó hiễn thị ra thằng $(".search-result").html(data);
                     $(".search-result").html(data); // add dữ liệu vô thằng .search-result
                     $(".search-result").show(); // chuyển từ display: none thành display:block
                     
                 });
             }
             
         },500);
         
     }); 
     



    // Hiển thị carousel for product thumnail
    $('main .product-detail .product-detail-carousel-slider .owl-carousel').owlCarousel({
        margin: 10,
        nav: true
        
    });
    // Bị lỗi hover ở bộ lọc (mobile) & tạo thanh cuộn ngang
    // Khởi tạo zoom khi di chuyển chuột lên hình ở trang chi tiết
    // $('main .product-detail .main-image-thumbnail').ezPlus({
    //     zoomType: 'inner',
    //     cursor: 'crosshair',
    //     responsive: true
    // });
    
    // Cập nhật hình chính khi click vào thumbnail hình ở slider
    $('main .product-detail .product-detail-carousel-slider img').click(function(event) { // cái hình được nhấn 
        /* Act on the event */
        $('main .product-detail .main-image-thumbnail').attr("src", $(this).attr("src")); // lấy src của hình featured
        var image_path = $('main .product-detail .main-image-thumbnail').attr("src"); 
        $(".zoomWindow").css("background-image", "url('" + image_path + "')");
        
    });  

    $(".product-container").hover(function(){
        $(this).children(".button-product-action").toggle(400);
    });

    // Display or hidden button back to top
    $(window).scroll(function() { 
		 if ($(this).scrollTop()) { 
			 $(".back-to-top").fadeIn();
		 } 
		 else { 
			 $(".back-to-top").fadeOut(); 
		 } 
	 }); 

    // Khi click vào button back to top, sẽ cuộn lên đầu trang web trong vòng 0.8s
	 $(".back-to-top").click(function() { 
		$("html").animate({scrollTop: 0}, 800); 
	 });

	 // Hiển thị form đăng ký
	 $('.btn-register').click(function () {
	 	$('#modal-login').modal('hide');
        $('#modal-register').modal('show');
    });

	 // Hiển thị form forgot password
	$('.btn-forgot-password').click(function () {
		$('#modal-login').modal('hide');
    	$('#modal-forgot-password').modal('show');
    });

	 // Hiển thị form đăng nhập
	$('.btn-login').click(function () {
    	$('#modal-login').modal('show');
    });

	// Fix add padding-right 17px to body after close modal
	// Don't rememeber also attach with fix css
	$('.modal').on('hide.bs.modal', function (e) {
        e.stopPropagation();
        $("body").css("padding-right", 0);
        
    });

    // Hiển thị cart dialog
    $('.btn-cart-detail').click(function () {
    	$('#modal-cart-detail').modal('show');
    });

    // Hiển thị aside menu mobile
    $('.btn-aside-mobile').click(function () {
        $("main aside .inner-aside").toggle();
    });

    $('main .product-detail .product-description .rating-input').rating({ // thư viện của ngôi sao, ở dòng đánh giá comment 
        min: 0,
        max: 5,
        step: 1,
        size: 'md',
        stars: "5",
        showClear: false,
        showCaption: false
    });

    $('main .product-detail .product-description .answered-rating-input').rating({// thư viện của ngôi sao, ở dòng đánh giá của người dùng
        min: 0,
        max: 5,
        step: 1,
        size: 'md',
        stars: "5",
        showClear: false,
        showCaption: false,
        displayOnly: false,
        hoverEnabled: true
    });

    $('main .ship-checkout[name=payment_method]').click(function(event) {
        /* Act on the event */
    });

    
    // Hiển thị carousel for relative products làm cho nó ko lập lại
    $('main .product-detail .product-related .owl-carousel').owlCarousel({
        loop:false,
        margin: 10,
        nav: true,
        dots:false,
        responsive:{
        0:{
            items:2
        },
        600:{
            items:4
        },
        1000:{
            items:5
        }
    }    
    });
     
});

// Login in google
function onSignIn(googleUser) {
    var id_token = googleUser.getAuthResponse().id_token;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://study.com/register/google/backend/process.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
      console.log('Signed in as: ' + xhr.responseText);
    };
    xhr.send('idtoken=' + id_token);
}

// Cập nhật giá trị của 1 param cụ thể
// ý tưởng là: http://shop.com/site/index.php?c=product&price-range=200000-300000&sort=price-asc
// Muốn giữ lại hết luôn  http://shop.com/site/index.php?c=product&price-range=200000-300000&sort=
// chỉ cập nhật lại giá trị price-asc
function getUpdatedParam(k, v) {//sort, price-asc
    var params={}; // Object 
    //params = {"c":"proudct", "category_id":"5", "sort": "price-desc"}
    // http://shop.com/site/index.php?c=product&price-range=100000-200000&sort=price-asc
    // window.location.search = "?c=product&price-range=100000-200000&sort=price-asc"

    // [?&]+([^=&]+)=([^&]*): cái nào thõa mãn cái này là str 
    // [?&] : có nghĩa là dấu ? hay dấu & cũng dc 
    // + : xuất hiện từ 1 tới n lần 
    // = : đơn giản là nó dấu "=" thôi
    // ^: là nó ko có dấu gì đấy, mang ý nghĩa phủ định
    // *: xuất hiện từ 1 đến n lần 
    // ([^=&]+): thõa cái này thì nó quăng vô cho key 
    // ([^&]*): thõa cái này thì nó quăng vô cho value 
    // "?c=product&price-range=100000-200000&sort=price-asc"
    // thằng thõa str là ?c=product: do 
    // có dấu ? , xuất hiện 1 lần, cái chữ "c" thõa là nó ko phải là dấu "=" hoặc dấu "&", xuất hiện 1 lần
    // tiếp theo là nó có dấu "=",  tiếp theo là thằng "product" nó ko phải là dấu & 
    // từ đó => 
    // str = ?c=product
    // key = c 
    // value = product
 
    window.location.search
      .replace(/[?&]+([^=&]+)=([^&]*)/gi, function(str,key,value) { // value : ?c=product , c , product, xem thêm tại biểu thức chính quy Regex
        params[key] = value;  // thêm thuộc tính vào trong Object 
        // alert(str);
        // alert(key);
        // alert(value);
      }
    );
   
    //{c:"proudct", price-range:"100000-200000", sort: "price-desc"}z
    //https://shop.com/site/index.php?c=product&category_id=1&sort= (Khi chọn "mặc định")
    params[k] = v; // có dòng này là do khi chúng ta lấy trên thằng URL ta được VD  
    // https://shop.com/san-pham?page=3
    // thì khi ta quay lại trang 1 thì thứ mà ta muốn thay đổi là thằng page= "3" thành page = "1" nên ta lấy cái value của nó cho bằng cái v, là cái số trang mà ta truyền vô, thì lúc này page=1, rồi xuống return ta truyền ngược lên 

    // khi mà mình chọn "mặc định" thì thằng là nó không có "v" =>params["sort"]="";
    // alert(JSON.stringify(params[k]));
    if (v == "") {
        delete params[k];
    }

    var x = [];//là array
    for (p in params) {
        //x[0] = 'c=product'
        //x[1] = 'price-range:"100000-200000"'
        //x[2] = 'sort=price-asc'
        x.push(p + "=" + params[p]);
        // alert(p);
    }
    return str_param = x.join("&");//c=product&price-range:"100000-200000"&sort=price-asc
}
// Paging
function goToPage(page,self) {
    // self: để có thể dò lên tìm cha nó 
    var data_url = $(self).closest("ul").attr("data-url");
    // alert( data_url);
    var str_param = getUpdatedParam("page", page);
    //ES6
    window.location.href =  `${data_url}?${str_param}`;
}
function displayCart(data) {

    var cart = JSON.parse(data);   //chuỗi Json từ dạng String thành dạng object
    // Nó sẽ thành giống như z 
    // {
    //     "items": {
    //       "3": {
    //         "img": "kemLamSangVungDaBikini.jpg",
    //         "name": "Kem làm sáng vùng da bikini Beaumore- 50ml",
    //         "product_id": "3",
    //         "qty": 3,
    //         "unit_price": "849000",
    //         "total_price": 2547000
    //       },
    //     },
    //     "total_product_number": 8,
    //     "total_price": 3597000
    //   }
    var total_product_number = cart.total_product_number;
    $(".btn-cart-detail .number-total-product").html(total_product_number);

    var total_price = cart.total_price;
    $("#modal-cart-detail .price-total").html(number_format(total_price)+"₫");
    var items = cart.items;
    var rows = "";
    for (let i in items) { // i: key, items: Object, lấy Object truyền zô "key" để lấy value
        let item = items[i];
        // item là 
        // {
        //         "img": "kemLamSangVungDaBikini.jpg",
        //         "name": "Kem làm sáng vùng da bikini Beaumore- 50ml",
        //         "product_id": "3",
        //         "qty": 3,
        //         "unit_price": "849000",
        //         "total_price": 2547000
        //  },
        var row = 
                '<hr>'+
                '<div class="clearfix text-left">'+   
                    '<div class="row">'+             
                        '<div class="col-sm-6 col-md-1">'+
                            '<div>'+
                                '<img class="img-responsive" src="../upload/' + item.img + '" alt="' + item.name + ' ">'+             
                            '</div>'+
                        '</div>'+
                        '<div class="col-sm-6 col-md-3">'+
                            '<a class="product-name" href="'+item.product_url+'">' + item.name + '</a>'+
                        '</div>'+
                        '<div class="col-sm-6 col-md-2">'+
                            '<span class="product-item-discount">' + number_format(Math.round(item.unit_price)) + '₫</span>'+
                        '</div>'+
                        '<div class="col-sm-6 col-md-3">'+
                            '<input type="hidden" value="1">'+
                            // this: là chính bản thân cái nút đó
                            // dấu "+": do đang là bên javascript nên nó là chuỗi thôi chứ ko liên qua gì mà + hết 
                            '<input type="number" onchange="updateProductInCart(this,'+ item.product_id +')" min="1" value="' + item.qty + '">'+
                        '</div>'+
                        '<div class="col-sm-6 col-md-2">'+
                            '<span>' + number_format(Math.round(item.total_price)) + '₫</span>'+
                        '</div>'+
                        '<div class="col-sm-6 col-md-1">'+
                            '<a class="remove-product" href="javascript:void(0)" onclick="deleteProductInCart('+ item.product_id +')">'+
                                '<span class="glyphicon glyphicon-trash"></span>'+
                            '</a>'+
                        '</div>'+ 
                    '</div>'+                                                   
                '</div>';
        rows += row; // để ta có thể cộng dồn lại 
    }
    $("#modal-cart-detail .cart-product").html(rows); // cart-product nằm ở thằng footer
}
 // Thay đổi số lượng sản phẩm trong giỏ hàng
 function updateProductInCart(self, product_id) { 
    var qty = $(self).val(); // lấy value cái nút đó VD :  item.qty 
    $.ajax({
        url: 'index.php?c=cart&a=update',
        type: 'GET',
        data: {product_id: product_id, qty: qty}
    })
    .done(function(data) {
        displayCart(data);
        
    });
}

function deleteProductInCart(product_id) {
    $.ajax({
        url: 'index.php?c=cart&a=delete',
        type: 'GET',
        data: {product_id: product_id}
    })
    .done(function(data) {
        displayCart(data);
        
    });
}
// Cập nhật các option cho thẻ select
function updateSelectBox(data, selector) {
    var items = JSON.parse(data); // biến từ array thành Object
    // [
    //     {
    //       "id": "271",
    //       "name": "Huyện Ba Vì"
    //     },
    //     {
    //       "id": "277",
    //       "name": "Huyện Chương Mỹ"
    //     },
    // ...
    // ]
    $(selector).find('option').not(':first').remove(); //
    // VD : $("main .district").find('option').not(':first').remove();
    // kiếm thằng  $("main .district"), tìm thằng 'option'
    // remove đi thằng ko phải con đầu tiên 
    // tức là remove đi thằng 
    // <?php foreach($districts as $district): ?>
    //     <option <?=$selected_district_id == $district->getId() ? "selected": ""?> value="<?=$district->getId()?>"><?=$district->getName()?></option>
    // <?php endforeach ?>
    if (!data) return; // data = null thì kết thúc 
    for (let i = 0; i < items.length; i++) {
        let item = items[i];
        let option = '<option value="' + item.id + '"> ' + item.name + '</option>';
        $(selector).append(option); // $("main .district") truyền thằng option ra phía sau nó, thì cái đoạn này dùng thay thế cho thằng code php 
    } 
    
}





