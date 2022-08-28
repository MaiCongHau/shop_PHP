<?php 
//Tổng cộng 6 cái:
// $provinces, $districts, $wards
// $selected_province_id, $selected_district_id, $selected_ward_id
// lấy mấy cái id này để khi chạy foreach thì khi id này nó match với id nào thì thêm cho nó class selected 
$provinceRepository = new ProvinceRepository();
$provinces = $provinceRepository->getAll();// lấy hết tên thành phố và type
$districts = [];
$wards = [];
// ý tưởng là lấy thằng $ward rồi suy ngược lại tỉnh và thành phố
$selected_ward = $customer->getWard(); // lấy ward_id của thằng $customer dò lên bảng ward thì trả về 1 cái Object
// giống z 
// object(Ward)[74]
//   protected 'id' => string '00007' (length=5)
//   protected 'name' => string 'Phường Cống Vị' (length=21)
//   protected 'type' => string 'Phường' (length=9)
//   protected 'district' => null
//   public 'district_id' => string '001' (length=3)

$selected_province_id = null;
$selected_district_id = null;
$selected_ward_id = null;
$shipping_fee = 0;
if (!empty($selected_ward)) { // nếu thằng này có dữ liệu vì ta 2 option là khách vãng lai và User nếu là User thì ta mới dùng dc 
    $selected_ward_id = $selected_ward->getId();// 2 selected_ward_id
    $selected_district = $selected_ward->getDistrict(); // thằng này lấy district_id dò lên bảng distric để lấy dc district, cũng sẽ trả về Object, như sau 
    // object(District)[75]
    //   protected 'id' => string '001' (length=3)
    //   protected 'name' => string 'Quận Ba Đình' (length=16)
    //   protected 'type' => string 'Quận' (length=6)
    //   protected 'province_id' => string '01' (length=2)
    $selected_district_id = $selected_district->getId();//3 selected_district_id
    $selected_province = $selected_district->getProvince(); // cũng như trên bỏ id do rồi dò lên lấy province, như sau
    // object(Province)[76]
    //   protected 'id' => string '01' (length=2)
    //   protected 'name' => string 'Thành phố Hà Nội' (length=22)
    //   protected 'type' => string 'Thành phố Trung ương' (length=25) 

    $selected_province_id = $selected_province->getId(); //4 selected_province_id
    $districts = $selected_province->getDistricts(); // 5 districts
    // lấy được $district sắp xếp theo thứ tự tăng dần, như sau
    //     array (size=30)
    //   0 => 
    //     object(District)[77]
    //       protected 'id' => string '271' (length=3)
    //       protected 'name' => string 'Huyện Ba Vì' (length=14)
    //       protected 'type' => string 'Huyện' (length=7)
    //       protected 'province_id' => string '01' (length=2)
    //   1 => 
    //     object(District)[78]
    //       protected 'id' => string '277' (length=3)
    //       protected 'name' => string 'Huyện Chương Mỹ' (length=21)
    //       protected 'type' => string 'Huyện' (length=7)
    //       protected 'province_id' => string '01' (length=2)
    //   2 => 
    //     object(District)[79]
    //       protected 'id' => string '018' (length=3)
    //       protected 'name' => string 'Huyện Gia Lâm' (length=16)
    //       protected 'type' => string 'Huyện' (length=7)
    //       protected 'province_id' => string '01' (length=2) 
    // ....
    $wards =  $selected_district->getWards(); // lấy id rồi dò lên cột ward 
    //     array (size=14)
    //   0 => 
    //     object(Ward)[107]
    //       protected 'id' => string '00007' (length=5)
    //       protected 'name' => string 'Phường Cống Vị' (length=21)
    //       protected 'type' => string 'Phường' (length=9)
    //       protected 'district_id' => string '001' (length=3)
    //   1 => 
    //     object(Ward)[108]
    //       protected 'id' => string '00031' (length=5)
    //       protected 'name' => string 'Phường Giảng Võ' (length=21)
    //       protected 'type' => string 'Phường' (length=9)
    //       protected 'district_id' => string '001' (length=3)
    // ... 
    $shipping_fee = $selected_province->getShippingFee(); // dựa vô province_id mà lấy fee ship, dc 
    //string '20000' (length=5)
}
// cái thứ cần lấy được là  $wards và $districts
 ?>
