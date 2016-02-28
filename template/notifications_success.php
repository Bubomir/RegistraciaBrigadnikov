<div class="panel notifications success">
                    <div class="row">
                        <div class="large-1 columns">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="100px" height="100px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                                <path d="M88.04,30.319L75.124,17.401c-0.454-0.453-1.067-0.709-1.71-0.709c-0.642,0-1.256,0.256-1.709,0.709L37.392,51.714
	                    l-9.094-9.093c-0.945-0.944-2.474-0.944-3.419,0L11.96,55.539c-0.453,0.453-0.709,1.068-0.709,1.709c0,0.641,0.256,1.256,0.709,1.71
	                       L35.607,82.6c0.453,0.453,1.067,0.708,1.709,0.708c0.029,0,0.055-0.016,0.083-0.016c0.024,0,0.05,0.014,0.075,0.014
	                   c0.621,0,1.236-0.236,1.709-0.708L88.04,33.738C88.985,32.794,88.985,31.264,88.04,30.319z" />
                            </svg>
                        </div>
                        <div class="large-11 columns">
                             <p>
                                 <?php echo (
                                    $row_notification_KTO['Change_Number'].' '
                                    .$row_notification_KTO['Surname'].' '
                                    .$row_notification_KTO['First_Name']
                                    ." odhlasil "
                                    .$row_notification_KOHO['Surname']
                                    .' zo smeny majstra '
                                    .$row_notification_KOHO['Change_Number'].' '
                                    .$row_notification_KOHO['First_Name'].' '
                                    .$row_notification_KOMU['Surname'].' '
                                    .$row_notification_KOMU['First_Name'].' '
                                    .$row_notification_KTO['Start_Date']);
                                 ?>
                            </p>
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
