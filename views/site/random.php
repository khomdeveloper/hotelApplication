<table><tr>
	<?php
	$count = 0;
	foreach ($email['friends'] as $friend) {
	    $count+= 1;
	    ?>
    	<td style="text-align:<?php
	    echo $count == 1
		    ? 'right'
		    : ($count == 2
			    ? 'center'
			    : 'left');
	    ?>; width:33%;">
		<?php
		$this->renderPartial('/site/random_friend', [
		    'email_variant' => [
			'name'		 => str_replace(' ', '<br/>', ThwUser::getShortName($friend['name'])),
			'href'		 => [
			    'bottom' => $friend['link']
			],
			'picture'	 => $friend['picture'],
			'second_label'	 => false,
			//'variant'	 => $friend['text'],
			'count'		 => $count
		    ]
		]);
		?>
    	    <script type="text/javascript">

    		Buttons.place({
		    refresh : true,
    		    host: $('.select_12_host_' + <?php echo $count; ?>),
    		    title: '<?php
	    T::w([
		'send3_thank_caps' => [
		    'en'	 => 'SAY THANK YOU',
		    'ru'	 => 'СКАЗАТЬ СПАСИБО'
		]
	    ]);
	    ?>',
    		    id: 'select_random_' + <?php echo $count; ?>,
    		    class: 'gold_button',
    		    outer: {
    			css: {
    			    'margin-right': '0px',
    			    'margin-top': '0px'
    			}
    		    },
    		    action: function(id) {
    			Base.wait(function() {
    			    return window.Send && window.Friends
    				    ? true
    				    : false;
    			}, function() {
    			    Send.selectByName('<?php echo $friend['name'] ?>');
			    $('.send_title').val('');
    			    //$('.send_title').val('<?php echo $friend['text']; ?>');
			    Send.send('with notify');
    			});
    		    }}); 
		    
    	    </script>

    	</td>
	    <?php
	} //of for friends
	?>
    </tr></table>