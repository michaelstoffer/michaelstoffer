---
title: PDO (PHP Data Objects) – Why I recommend them
excerpt: PHP Data Objects, also known as PDO, is an interface for accessing databases in PHP without tying code to a specific database. This is my favorite method...
cover: /media/blog/php-data-objects-pdo-why-i-recommend-them/cover.webp
published_at: "2016-08-12 08:00:00 -0400"
updated_at: "2016-08-12 08:00:00 -0400"
tags: ["Development"]
---

PHP Data Objects, also known as PDO, is an interface for accessing databases in PHP without tying code to a specific database. This is my favorite method of accessing the database because who wants to tie all of their eggs to one basket? Rather than directly calling the MySQL, MySQLi, and PG extensions, developers can use the PDO interface, simplifying the porting of applications to other databases.

PHP Data Objects (PDO) doesn’t account for database-specific syntax, but still allows for switching databases and platforms to be mostly painless, by just switching the connection string in most cases.

PHP Data Objects can be used on any database that has a PDO driver written for it. At the time of writing this article, the following drivers and databases are supported:

<table>
<thead>
<tr>
  <th>Driver name</th>
  <th>Supported Databases</th>
</tr>
</thead>
<tbody>
<tr>
  <td>PDO_CUBRID</td>
  <td>Cubrid</td>
</tr>
<tr>
  <td>PDO_DBLIB</td>
  <td>FreeTDS / Microsoft SQL Server / Sybase</td>
</tr>
<tr>
  <td>PDO_FIREBIRD</td>
  <td>Firebird</td>
</tr>
<tr>
  <td>PDO_IBM</td>
  <td>IBM DB2</td>
</tr>
<tr>
  <td>PDO_INFORMIX</td>
  <td>IBM Informix Dynamic Server</td>
</tr>
<tr>
  <td>PDO_MYSQL</td>
  <td>MySQL 3.x/4.x/5.x</td>
</tr>
<tr>
  <td>PDO_OCI</td>
  <td>Oracle Call Interface</td>
</tr>
<tr>
  <td>PDO_ODBC</td>
  <td>ODBC v3 (IBM DB2, unixODBC and win32 ODBC)</td>
</tr>
<tr>
  <td>PDO_PGSQL</td>
  <td>PostgreSQL</td>
</tr>
<tr>
  <td>PDO_SQLITE</td>
  <td>SQLite 3 and SQLite 2</td>
</tr>
<tr>
  <td>PDO_SQLSRV</td>
  <td>Microsoft SQL Server / SQL Azure</td>
</tr>
<tr>
  <td>PDO_4D</td>
  <td>4D</td>
</tr>
</tbody>
</table>

Some of these databases probably won’t be supported on your system, but here is an easy way to tell which are:

<pre><code class="language-php">&lt;?php
    print_r(PDO::getAvailableDrivers());
?&gt;
</code></pre>

We are only going to talk about the MySQL database method right now, but keeping in mind that when using PHP Data Objects, we have the ability to change that one day without the need of rewriting a lot of code to do so.

<h2>Connecting to MySQL Database</h2>

A connection to MySQL using PDO should be done by using a try/catch statement. This will allow you to catch any connection errors. You can do this by doing the following:

<pre><code class="language-php">&lt;?php
try {
    $db = new PDO("mysql:host=$db_address;dbname=$db_name", $db_username, $db_secure);
} catch(PDOException $e) {
    $error_log = fopen('pdo_connection_errors.log', 'a');
    fwrite($error_log, "[" . date("F jS, Y @ g:i:s", time()) . "] ERROR: " . $e-&gt;getMessage() . ":: in file " . __FILE__ . " on line " . __LINE__ . "\n");
    fclose($error_log);
    die ("ERROR: " . $e-&gt;getMessage());
}
?&gt;
</code></pre>

There is a vast amount of information on PHP Data Objects (PDO) that can be found on PHP.net. This is where I spent most of my time learning PDO.

<h2>Insert and Update Statements</h2>

Inserting new data, or updating existing data is probably one of the most common things we do with databases. Here is how I would write an INSERT statement using PDO:

<pre><code class="language-php">&lt;?php
    $query = "INSERT INTO table (col1,col2,col3) VALUES (:col1,:col2,:col3)";
    $statement = $db-&gt;prepare( $query );
    $statement-&gt;execute( array( ':col1' =&gt; $col1, ':col2' =&gt; $col2, ':col3' =&gt; $col3 ) );
?&gt;
</code></pre>

And here is an UPDATE statement:

<pre><code class="language-php">&lt;?php
    $query = "UPDATE table SET col2 = :col2 WHERE col1 = :col1 and col3 = :col3 limit 1";
    $statement = $db-&gt;prepare( $query );
    $statement-&gt;execute( array( ':col2' =&gt; $col2, ':col1' =&gt; $col1, ':col3' =&gt; $col3 ) );
?&gt;
</code></pre>

There are two reasons why I write my statements like this. The first reason being that when you write out a statement in this matter, you aren’t as vulnerable to SQL injection attacks as you would be if you just included the variable within the query. The second reason being that if you wanted to do multiple insert statements using the same query, writing a prepared statement is a simple way to do this.

<pre><code class="language-php">&lt;?php
    $query = "INSERT INTO table (col1,col2,col3) VALUES (:col1,:col2,:col3)";
    $statement = $db-&gt;prepare( $query );
    $statement-&gt;execute( array( ':col1' =&gt; $col1, ':col2' =&gt; $col2, ':col3' =&gt; $col3 ) );
    $statement-&gt;execute( array( ':col1' =&gt; $col1, ':col2' =&gt; $col2, ':col3' =&gt; $col3 ) );
    $statement-&gt;execute( array( ':col1' =&gt; $col1, ':col2' =&gt; $col2, ':col3' =&gt; $col3 ) );
?&gt;
</code></pre>

The reason I like to use PDO is because things change all the time. You never know if one day, the database you are using will all of a sudden be the slowest thing out there. If this is the case, PDO gives you the ability to change your database without the hassle of having to re-write all of your code. There still may be a few lines that you will have to change, but not everything. That is why I recommend using it.

I hope this shows how simple PHP Data Objects can be and also how useful. There is so much more that could be covered, but I just thought that I would give a brief run down of PHP Data Objects and how to use them.
