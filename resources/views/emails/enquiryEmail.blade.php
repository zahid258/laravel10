<!doctype html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <title><?php if($data['type'] =='sendToCustomer'){ ?> Inqiry Letter <?php } ?></title>

</head>

<body>
    <?php if($data['type'] =='sendToCompany'){ ?>

        <strong>Name:</strong> <span> <?php echo $data['customer_name'] ?></span> <br>
        <strong>Country:</strong> <span>  <?php echo $data['customer_country']; ?></span> <br>
        <strong>Phone:</strong> <span>   <?php echo $data['customer_phone']; ?></span> <br>
        <strong>Remarks:</strong> <span>  <?php echo $data['customer_remarks']; ?></span> <br>
        <strong>Car Stock:</strong> <span> <?php echo $data['car_id']; ?> </span>

     <?php } ?>
<?php if($data['type'] =='sendToCustomer'){ ?>
<style>
    .quote p {
        margin: 5px 0;
        font-size: 13px !important;
    }

    table {
        border-collapse: collapse;
    }

    .gray {
        background: #eee;
    }
</style>
<div style="max-width:622.0px;margin:0 auto;font-family:'Helvetica Neue','Helvetica',Helvetica,Arial,sans-serif;padding:0;font-size:12px;line-height:1">
    <div style="padding:0.2% 0.0px 0.0px 0.2%">

        <img style="width:620px" src="https://janjapan.com/resources/images/other-mail-header.jpg" class="CToWUd a6T"
             tabindex="0">
        <div class="a6S" dir="ltr" style="opacity: 0.01; left: 819.4px; top: 95.9px;">
            <div id=":21m" class="T-I J-J5-Ji aQv T-I-ax7 L3 a5q" role="button" tabindex="0"
                 aria-label="Download attachment " data-tooltip-class="a1V" data-tooltip="Download">
                <div class="aSK J-J5-Ji aYr"></div>
            </div>
        </div>
        <div class="a6S" dir="ltr" style="opacity: 0.01;"></div>
    </div>

    <div style="max-width:622px;margin:0 auto 25px" class="quote">

        <p style="font-size:16px;font-weight:bold;">
            <?php $max_enquriy_id = $data['enquiry_id'] + 1; ?>
            <tr>
                <td style="padding:0 0 5px 0px;font-size:13px">
                    <span style="color:red"> Please do not reply to this email address as this is an unattended mail box.</span><br>
                    <br>
                    <a style="text-decoration:underline;"
                       href="https://janjapan.com/used-car/<?php echo strtolower(str_replace(array(' ', '_', '.'), array('-'), $data['car_detail'][0]->country.'-'.$data['car_detail'][0]->country_id)); ?>/<?php echo strtolower(str_replace(array(' ', '_', '.'), array('-'), $data['car_detail'][0]->maker_name)); ?>/<?php echo strtolower(str_replace(array(' ', '_', '.'), array('-'), $data['car_detail'][0]->model_name)); ?>/<?php echo $data['car_detail'][0]->car_id; ?>"
                       target="_blank">
                        <span style="color:blue">TO REPLY PLEASE CLICK HERE</span>
                    </a></td>
            </tr>
            <br>
            Dear <?php echo ucwords($data['enquirer_data']['customer_name']); ?>;
            <!--<a target="_blank" href="<?php /*echo ucwords($data['enquirer_data']['customer_email']); */ ?>"><?php /*echo $data['enquirer_data']['customer_email']; */ ?></a>-->
            <br>
            <!--<b><?php /*echo ucwords($data['enquirer_data']['customer_phone']); */ ?></b><br>
            <b><strong><?php /*echo ucwords($data['enquirer_data']['customer_country']); */ ?></strong> &nbsp;<?php /*echo ucwords($data['enquirer_data']['customer_city']); */ ?></b>-->
        </p>
        <p style="font-size:16px;margin:0">Detail information on your enquired vehicle:</p>
        <?php foreach ($data['car_detail'] as $car) { ?>
            <h2 style="font-size:18px;color:#0068cf;margin:0">
                <!--<span style="display:inline-block;width:6px;min-height:18px;margin:0 5px 5px 0;vertical-align:middle;background:#fccf3a">&nbsp;</span>-->
                <?php echo $car->maker_name . '  ' . $car->model_name . '  ' . $car->registration_year . ' / ' . $car->registration_month; ?>
                &nbsp;Enquiry No <?php echo $max_enquriy_id; ?> &nbsp;Stock No <?php echo $data['car_detail'][0]->car_id; ?>
            </h2>
            <div style="overflow:hidden;font-size:0;margin:5px 0 5px">
                <a href="#">
                    <img alt="image"
                         src="<?php echo config('IMAGEPATH.S3_IMAGE_PATH') . $car->images[0]->directory . '/' . $car->images[0]->car_id . '/' . $car->images[0]->file_name; ?>"
                         style="float:left" class="m_-9077734990190785608CToWUd CToWUd" width="180" height="135">
                </a>
                <table style="width:437px;table-layout:fixed;border:1px solid #707070;border-collapse:collapse;float:right;font-size:12px">
                    <tbody>


                    <tr>
                        <td colspan="4" style="border:1px solid #323232;text-align:center;font-weight:bold;color:#000">
                            <strong>Chassis No: <?php echo $car->chassis_no; ?></strong></td>
                    </tr>
                    <!--<tr>
                        <td colspan="4" style="border:1px solid #323232;text-align:center"><?php /*echo $car->chassis_no; */ ?></td>
                    </tr>-->


                    <!--<tr>
                        <th style="font-weight:bold;color:#000;padding:2px" colspan="4"><strong>Car Details</strong>
                        </th></tr>-->
                    <tr>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px;background:#f1f1f1">Stock No</td>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px"><?php echo $car->car_id; ?></td>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px;background:#f1f1f1">Maker/Model</td>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px"><?php echo $car->maker_name; ?>
                            /<?php echo $car->model_name; ?></td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px;background:#f1f1f1;">Reg.Year</td>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px"><?php echo $car->registration_year; ?></td>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px;background:#f1f1f1">Doors</td>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px"><?php echo $car->doors; ?></td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px;background:#f1f1f1;">Displacement
                            (cc)
                        </td>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px"><?php
                            if ($car->engine_size > 0)
                                echo $car->engine_size . ' CC';
                            ?></td>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px;background:#f1f1f1">Mileage</td>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px"><?php
                            if ($car->mileage > 0)
                                echo $car->mileage . ' KM';
                            ?></td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px;background:#f1f1f1;">Steering</td>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px"><?php echo $car->steering_name; ?></td>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px;background:#f1f1f1">Transmission</td>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px"><?php echo $car->transmission_name; ?></td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px;background:#f1f1f1;">Fuel</td>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px"><?php echo $car->fuel_name; ?></td>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px;background:#f1f1f1">Color</td>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px"><?php
                            if (isset($data['ext_color']->color_name) && !empty($data['ext_color']->color_name))
                                echo $data['ext_color']->color_name;
                            ?></td>
                    </tr>

                    <tr>
                        <td colspan="4"
                            style="border:1px solid #323232;background-color:#000; text-align:center; color:#fff; border-collapse:collapse;">
                            Destination
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px;background:#f1f1f1;">Destination</td>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px"><?php echo $car->country; ?></td>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px;background:#f1f1f1">
                            <!--Destination Port--></td>
                        <td style="border:1px solid #707070;padding:2px 0 2px 5px"><?php
                             ?></td>
                    </tr>
                    </tbody>
                </table>
                </br>


            </div>
            <?php
        }
        ?>



        <table style="background-color: #fff;margin-top: 15px;" width="622" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td style="font-size:13px">For further Information regarding prices with insurance, freight, inspection
                    cost etc, please feel free to contact our local office on the contact detail give below.
                </td>
            </tr>
            <br>
            <?php if (!empty($data['contact_information'])) {  ?>
            <tr>
                <td style="font:13px Arial,Helvetica,sans-serif;color:#000;padding:0px 0px 8px 0px;line-height:18px">
                    <?php if(!empty($data['contact_information'][0]->address)){  ?> <strong style="color:#000000;font-size:14px">Address:</strong><br>
                    <span style="color:#000;font-size:13px"><?php echo $data['contact_information'][0]->address;  ?><!--Plot No. 42/43, Quetta Town, Scheme 33, Super Highway, Karachi, Pakistan--></span> <?php } ?>
                    <br>
                    <?php if(!empty($data['contact_information'][0]->phone)){ ?><strong style="font-size:14px">Phone:</strong><a style="color:#1155cc" href="#"> <?php echo $data['contact_information'][0]->phone;  ?> </a><br> <?php } ?>
                    <?php if(!empty($data['contact_information'][0]->email)){ ?><strong style="font-size:13px">Email:</strong> <a style="color:#1155cc"
                    <a href="mailto:&lt;?php echo $data['contact_information'][0]-&gt;email; ?&gt;"
                                                                      target="_blank"><?php echo $data['contact_information'][0]->email; ?>&gt;</a> <?php } ?>
                </td>
            </tr>
            <?php } ?>


            <tr>
                <!--  <td style="padding:0 0 5px 0px;font-size:13px">
                      <a style="text-decoration:underline" href="https://janjapan.com/" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=https://janjapan.com/&amp;source=gmail&amp;ust=1525503168958000&amp;usg=AFQjCNGxWwPDqs6MrWcxf4b_-quS46jGyQ">Please do visit us again for checking new stock.</a>
                      <span style="color:#000"> We are adding variety of new stock on regular basis.</span></td>-->
            </tr>


            </tbody>
        </table>
        <?php if (!empty($data['staff_detail'])) { ?>
            <table width="622px" cellpadding="3" cellspacing="0" border="1">
                <tr>
                    <th style="background:#000000;color:#fff;font-size: 13px;"><strong>Sales Person</strong></th>
                    <th style="background:#000000;color:#fff;font-size: 13px;"><strong>Designaton</strong></th>
                    <th style="background:#000000;color:#fff;font-size: 13px;"><strong>Phone No.</strong></th>
                </tr>
                <?php
                $i = 0;
                foreach ($data['staff_detail'] as $sdetail) {
                    $i++;
                    ?>
                    <tr class="gray">
                        <td style="font-size: 12px;text-align: left; padding: 0 10px;"><?php echo $sdetail->member_name; ?> </td>
                        <td style="font-size: 12px;text-align: left;padding: 0 10px;"> <?php echo $sdetail->member_designation; ?></td>
                        <td style="font-size: 12px; text-align: left;padding: 0 10px;"> <?php echo $sdetail->member_phone; ?></td>
                    </tr>
                    <?php
                }
                ?>
                <!--<tr>
                    <td style="font-size: 12px;text-align: left;padding: 0 10px;">Karachi Staff </td>
                    <td style="font-size: 12px;text-align: left;padding: 0 10px;">Sales Coordinator  </td>
                    <td style="font-size: 12px; text-align: left;padding: 0 10px;">0333 0132036  </td>
                </tr>
                <tr class="gray">
                    <td style="font-size: 12px;text-align: left;padding: 0 10px;">Hidayat Ullah  </td>
                    <td style="font-size: 12px;text-align: left;padding: 0 10px;">Sales Manager </td>
                    <td style="font-size: 12px; text-align: left;padding: 0 10px;">0333 0132033   </td>
                </tr>
                <tr>
                    <td style="font-size: 12px;text-align: left;padding: 0 10px;">Ahmed Ullah  </td>
                    <td style="font-size: 12px;text-align: left;padding: 0 10px;">Sales Manager  </td>
                    <td style="font-size: 12px; text-align: left;padding: 0 10px;">0333 0132034   </td>
                </tr>
                <tr class="gray">
                    <td style="font-size: 12px;text-align: left;padding: 0 10px;">Muhammed Ahmed </td>
                    <td style="font-size: 12px;text-align: left;padding: 0 10px;">Business Development Manager  </td>
                    <td style="font-size: 12px; text-align: left;padding: 0 10px;">0333 0132038   </td>
                </tr>
                <tr>
                    <td style="font-size: 12px;text-align: left;padding: 0 10px;">Asad Ullah  </td>
                    <td style="font-size: 12px;text-align: left;padding: 0 10px;">Sales Manager  </td>
                    <td style="font-size: 12px; text-align: left;padding: 0 10px;">0333 0132035  </td>
                </tr>
                <tr class="gray">
                    <td style="font-size: 12px;text-align: left;padding: 0 10px;">Atta Jan </td>
                    <td style="font-size: 12px;text-align: left;padding: 0 10px;">Salesman  </td>
                    <td style="font-size: 12px; text-align: left;padding: 0 10px;">0333 0132037  </td>
                </tr>
                <tr>
                    <td style="font-size: 12px;text-align: left;padding: 0 10px;">Abdul Qadir  </td>
                    <td style="font-size: 12px;text-align: left;padding: 0 10px;">CRO  </td>
                    <td style="font-size: 12px; text-align: left;padding: 0 10px;">0333 0132039   </td>
                </tr>-->
            </table>
            <?php
        }
        ?>
        <br>
        <tr>
            <td style="padding:5px 0; font-size: 13px;">Regards<br>
                <span style="font-weight:bold"><b>Jan Japan Support Team</b></span></td>
        </tr>

        <div class="adL">


        </div>
    </div>
    <div class="adL">


    </div>
</div>
<?php } ?>
</body>
</html>
