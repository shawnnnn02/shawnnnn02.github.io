<?php
function pro_img($pimage)
{
    if ($pimage == null){
        return "<img src='image/default-product-image' width=50 height=50 alt='product_default'/>";
    }
    else
    {
        return "<img src= 'uploads/$pimage' width=50/>";
    }
}
function user_img($user_image)
{
    if ($user_image == null){
        return "<img src='image/default-profile' width=50 height=50 alt='user_default'/>";
    }
    else
    {
        return "<img src= 'uploads/$user_image' width=50/>";
    }
}
?>
