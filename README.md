Wiser
=====

[![Build Status](https://secure.travis-ci.org/albertofem/wiser.png?branch=master)](http://travis-ci.org/albertofem/wiser)

Wiser is **not** a template engine. It aims to provide enhanced features for the PHP template engine. It's highly inspired in [Savant3](http://phpsavant.com).

Usage
---------

Basic usage example:

    $config = array('template_path' => __DIR__ . '/views/);

    use Wiser\Wiser;
    
    $wiser = new Wiser($config);
    $wiser->render('template.html.php', array('myVar' => 'test'));

In your template:

If you're using **PHP >= 5.4.***

    <?= $myVar; ?>

If you're using **PHP <= 5.3.***

    <?php echo $myVar; ?>