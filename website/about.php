<!DOCTYPE html>
<!--
 -    Copyright (c) 2020 Ben Cerjan, Lief Esbenshade
 -
 -    This file is part of Easy Match.
 -
 -    Easy Match is free software: you can redistribute it and/or modify
 -    it under the terms of the GNU Affero General Public License as published by
 -    the Free Software Foundation, either version 3 of the License, or
 -    (at your option) any later version.
 -
 -    Easy Match is distributed in the hope that it will be useful,
 -    but WITHOUT ANY WARRANTY; without even the implied warranty of
 -    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 -    GNU Affero General Public License for more details.
 -
 -    You should have received a copy of the GNU Affero General Public License
 -    along with Easy Match.  If not, see <https://www.gnu.org/licenses/>.
-->

<html lang="en">
<head>
<meta charset="utf=8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<?php include("./resources.php"); ?>


<title>About Easy Match</title>
</head>

<body>

<?php include("header.php"); ?>

<main role="main">
<div class="container">
  <h2>About Easy Match</h2>
  <p>Have someone (human, cat, iguana, we don't judge) coming into your life that needs a name?
     This website is designed to allow users to pair with one another
     to find names that they both love. Sign up with as many partners or friends as you like,
     then start picking the names that speak to you. 'My Matches!' will update you
     whenever you and your partner both like the same name. Happy name matching!</p>

  <h2>About the Data</h2>
   <p>The set of names that are used on this site are drawn from
      the <a href="https://www.ssa.gov/OACT/babynames/limits.html"> US Social Security database</a>.
      That means that any name that was given to at least 5 newborns in a year since 1880
      is inside the database (somewhere, there are a lot of dusty corners around
      here). </p>

   <h3> Filter Definitions</h3>
    <p>We give you the ability to apply filters to the names you see to help you
      find the names you like more quickly.
      <ul>
        <li> Gender is defined using the data provided by
          the social security administration. We use the last ten years of data to determine
          name/gender associations. "Traditionally Boys" and "Traditionally Girls"
          names are names where more than 95% of the babys born are of that gender.
          We define "gender neutral names" as names where between 20% and 80% of the
          babies given that name are identified as male.</li>
        <li> Popularity is determined using the names average annual rank. By default,
          we show you names that are in the top 500 most popular."Popular names only"
          will only show names that are in the top 250. "Unusual names only" will show
          you names that are not in the top 500.  </li>
      </ul>
    </p>


  <h2>About Privacy</h2>
  <p>See our <a href='privacy.php'>official privacy policy</a> here.
     The short version is: we will never sell your data. We will be analyzing your usage
     of the site to try and make it better, and to see if we can learn anything cool
     about how people pick baby names. To that end, we may also share anonymized selection
     and partnering data with other organizations.
     You can delete your  account at any time, at which point any identifiable data about you
     will be deleted. We will also delete your partner data and selection data from our servers,
     but your anonymized data will remain part of any datasets we have shared (as we no longer
     control it, we cannot guarantee deletion). You can request a copy of all data we have stored
     about you at any time. You can opt-out of data sharing, view your data, and delete your account <a href='account.php'> here</a>.</p>

  <h2>Open Source</h2>
  <p>This website is fully open source under the AGPL license. You can view the
    source code <a href="https://github.com/waldoCorp/easy-match"> here</a>.</p>

  <h2>waldoCorp</h2>
  <p> Easy Match is hosted as a service of waldoCorp, which is just
     a fancy way of saying that <a href='https://ben.waldocorp.com'>Ben</a>
     and <a href='https://liefesbenshade.com'>Lief</a> made it and we hope
     that it is useful for you.

     waldoCorp is not (yet) a registered corporation, but its the name Ben and Lief picked
     as an umbrella for various projects.
     Its named for Waldo, an adorable cat who started life in a college dorm room,
     and now is the Queen of Ben's apartment. Easy Match is our first product, and we
     hope to create more cool things in the future.</p>

</div>
</main>
<?php include("footer.php"); ?>

</body>

</html>
