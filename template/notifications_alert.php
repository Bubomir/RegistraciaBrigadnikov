
<div class="panel notifications alerts">
    <div class="row">
        <div class="large-1 columns">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="100px" height="100px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                <path d="M84.707,68.752L65.951,49.998l18.75-18.752c0.777-0.777,0.777-2.036,0-2.813L71.566,15.295
	c-0.777-0.777-2.037-0.777-2.814,0L49.999,34.047l-18.75-18.752c-0.746-0.747-2.067-0.747-2.814,0L15.297,28.431
	c-0.373,0.373-0.583,0.88-0.583,1.407c0,0.527,0.21,1.034,0.583,1.407L34.05,49.998L15.294,68.753
	c-0.373,0.374-0.583,0.88-0.583,1.407c0,0.528,0.21,1.035,0.583,1.407l13.136,13.137c0.373,0.373,0.881,0.583,1.41,0.583
	c0.525,0,1.031-0.21,1.404-0.583l18.755-18.755l18.756,18.754c0.389,0.388,0.896,0.583,1.407,0.583c0.511,0,1.019-0.195,1.408-0.583
	l13.138-13.137C85.484,70.789,85.484,69.53,84.707,68.752z" />
            </svg>

        </div>
        <div class="large-11 columns">
            <p><?php echo ("Majster ".$row_notification_KTO['Surname'].' '.$row_notification_KTO['First_Name']." prihlásil ".$row_notification_KOHO['Surname'].' '.$row_notification_KOHO['First_Name']." na rannú zmenu majstrovi  na tento dátm. "); ?></p>
        </div>
        <div class="large-1 columns">
            <svg version="1.1" id="time-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="100px" height="100px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                <g>
                    <g>
                        <path d="M50,22.44c15.198,0,27.56,12.367,27.56,27.562c0,15.197-12.362,27.559-27.56,27.559
			c-15.199,0-27.561-12.362-27.561-27.559C22.439,34.806,34.801,22.44,50,22.44 M50,12.5c-20.712,0-37.5,16.792-37.5,37.502
			C12.5,70.712,29.288,87.5,50,87.5c20.712,0,37.5-16.788,37.5-37.498C87.5,29.292,70.712,12.5,50,12.5L50,12.5z" />
                    </g>
                    <path d="M69.195,36.068l-3.897-3.902c-0.743-0.747-2.077-0.729-2.791,0L50.022,44.654l-6.863-6.863
		c-0.743-0.743-2.046-0.743-2.789,0l-3.892,3.893c-0.372,0.364-0.585,0.873-0.585,1.402c0,0.525,0.204,1.025,0.578,1.394
		l12.133,12.133c0.374,0.374,0.869,0.578,1.396,0.578c0.027,0,0.051,0,0.078,0c0.517-0.009,1-0.213,1.364-0.578l17.754-17.754
		C69.965,38.087,69.965,36.835,69.195,36.068z" />
                </g>
            </svg>

        </div>
        <div class="large-11 columns ">
            <div class="clock-notifications">
                <?php echo $row_notification_KTO['TimeStamp']; ?>
            </div>
        </div>
    </div>
</div>
