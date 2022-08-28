<div class="row">

<!-- oninvalid="this.setCustomValidity('Vui lòng nhập tên của bạn')" : đơn giản là thông báo nếu người dùng ko nhập zô thẻ input  -->
<!--  oninput="this.setCustomValidity('')" : nghĩa là khi người dùng nhập vào bất kể cái gì ở thẻ input này thì nó gợi ý là '' -->
    <div class="form-group col-sm-6">
        <input type="text" value="<?=$customer->getShippingName()?>" class="form-control" name="fullname"
        placeholder="Họ và tên" required=""
        oninvalid="this.setCustomValidity('Vui lòng nhập tên của bạn')" 
        oninput="this.setCustomValidity('')">
    </div>
    <div class="form-group col-sm-6">
        <input type="tel" value="<?=$customer->getShippingMobile()?>" class="form-control" name="mobile"
        placeholder="Số điện thoại" required="" pattern="[0][0-9]{9,}"
        oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại bắt đầu bằng số 0 và ít nhất 9 con số theo sau')"
        oninput="this.setCustomValidity('')">
    </div>
    <div class="form-group col-sm-4">
        <select name="province" class="form-control province" required=""
        oninvalid="this.setCustomValidity('Vui lòng chọn Tỉnh / thành phố')"
        oninput="this.setCustomValidity('')">
        <!-- $provinces lấy hết cột trong bảng province luôn -->
        <!-- $selected_province_id = 01  -->
        <!-- Hà Nội -->
        <option value="">Tỉnh / thành phố</option>
        <?php foreach($provinces as $province): ?>
            <option <?=$selected_province_id == $province->getId() ? "selected": ""?> value="<?=$province->getId()?>"><?=$province->getName()?></option>
        <?php endforeach ?>
    </select>
</div>
<div class="form-group col-sm-4">
    <select name="district" class="form-control district" required=""
    oninvalid="this.setCustomValidity('Vui lòng chọn Quận / huyện')"
    oninput="this.setCustomValidity('')">
    <option value="">Quận / huyện</option>

    <!-- // lấy được $district sắp xếp theo thứ tự tăng dần, như sau
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
    // .... -->
    <!-- $selected_district_id = 001 -->
    <!-- Quận Ba Đình -->
    <?php foreach($districts as $district): ?>
        <option <?=$selected_district_id == $district->getId() ? "selected": ""?> value="<?=$district->getId()?>"><?=$district->getName()?></option>
    <?php endforeach ?>
</select>
</div>
<div class="form-group col-sm-4">
    <select name="ward" class="form-control ward" required=""
    oninvalid="this.setCustomValidity('Vui lòng chọn Phường / xã')"
    oninput="this.setCustomValidity('')">
    <option value="">Phường / xã</option>
    <!-- array (size=14)
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
    // ...  -->
    <!-- $selected_ward_id = 0007 -->
    <!-- Phường Cống Vị -->
    <?php foreach($wards as $ward): ?>
        <option <?=$selected_ward_id == $ward->getId() ? "selected": ""?> value="<?=$ward->getId()?>"><?=$ward->getName()?></option>
    <?php endforeach ?>
</select>
</div>
<div class="form-group col-sm-12">
    <input type="text" value="<?=$customer->getHousenumberStreet()?>" class="form-control" placeholder="Địa chỉ"
    name="address" required=""
    oninvalid="this.setCustomValidity('Vui lòng nhập địa chỉ bao gồm số nhà, tên đường')"
    oninput="this.setCustomValidity('')">
</div>
</div>