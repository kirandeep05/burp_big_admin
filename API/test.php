<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form action="index.php" method="post">
        <fieldset>
            <legend>Quick Search</legend>
            <table>
                <tr><td><input type="hidden" name="type" value="quick_search" /><input type="submit" value="Get" /></td></tr>
            </table>
        </fieldset>
        </form>
        
        <form action="index.php" method="post">
        <fieldset>
            <legend>Trending Ads</legend>
            <table>
                <tr><td><input type="text" name="city_id" /></td></tr>
                <tr><td><input type="hidden" name="type" value="trending_ads" /><input type="submit" value="Get" /></td></tr>
            </table>
        </fieldset>
        </form>
        
        <form action="index.php" method="post">
        <fieldset>
            <legend>Sponsored Ads</legend>
            <table>
                <tr><td><input type="text" name="city_id" /></td></tr>
                <tr><td><input type="hidden" name="type" value="sponsored_ads" /><input type="submit" value="Get" /></td></tr>
            </table>
        </fieldset>
        </form>
        
        <form action="index.php" method="post">
        <fieldset>
            <legend>Get Cities</legend>
            <table>                
                <tr><td><input type="hidden" name="type" value="get_cities" /><input type="submit" value="Get" /></td></tr>
            </table>
        </fieldset>
        </form>
        
        <form action="index.php" method="post">
        <fieldset>
            <legend>Get Single Rest</legend>
            <table>
                <tr><td><input type="text" name="hotel_id" /></td></tr>
                <tr><td><input type="hidden" name="type" value="get_single_rest" /><input type="submit" value="Get" /></td></tr>
            </table>
        </fieldset>
        </form>
       
        <form action="index.php" method="post">
        <fieldset>
            <legend>Get Single Rest</legend>
            <table>
                <tr><td>Search type</td><td><input type="text" name="search_type" /></td></tr>
                <tr><td>Search type ID</td><td><input type="text" name="search_type_id" /></td></tr>
                <tr><td><input type="hidden" name="type" value="quick_search_result" /><input type="submit" value="Get" /></td></tr>
            </table>
        </fieldset>
        </form>

         <form action="index.php" method="post">
                <fieldset>
                    <legend>Search Hotel By Text</legend>
                    <table>
                        <tr><td>Search</td><td><input type="text" name="search_value" /></td></tr>
                        <tr><td><input type="hidden" name="type" value="search" /><input type="submit" value="Get" /></td></tr>
                    </table>
                </fieldset>
                </form>
        
    </body>
</html>
