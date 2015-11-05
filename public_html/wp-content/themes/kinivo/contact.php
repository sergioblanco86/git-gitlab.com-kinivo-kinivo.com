<?php
/*
Template Name: Contact
*/
?>
<?php get_header(); ?>
		<!-- Content! -->
		<div class="contentWeb">
			
			<div class="contact-content">
				<div class="wrapper wrap">
					<h1>Contact us</h1>
					<h2>Here's how you can get in touch with someone at kinivo</h2>
					<div class="contact-form">
						<iframe src="https://support.kinivo.com/customer/widget/emails/new?" id="emailForm"></iframe>
                                                <SCRIPT>
                                                       params = location.search;
                                                       frame = document.getElementById("emailForm");
                                                       frame.src = frame.src+params;
                                                </SCRIPT>
					</div>
					<div class="contact-information">
						<p style='font-size:16px !important; margin-top:-80px;'>Visit <a href='https://support.kinivo.com' style='color:#aac902; text-decoration:underline;'>support.kinivo.com</a> for drivers, manuals, how-to guides,<BR>and help with set-up and troubleshooting.</p>
						<h1>Contact Information</h1>
						<h2>Call us</h2>
						<p style='color:#aa0000;' id='callstatus'>Current Status: Closed</p>
						<p id='pnums' style='color:#dddddd;'>1-855-4-KINIVO (Toll-free)<br />
						(855-454-6486)<br />
						Monday to Friday:<br />
					  	9AM - 5PM Pacific Time</p>
					  	<h2>Email us</h2>
						<p class="last-info-contact"><a  href="mailto:support@kinivo.com" class="support-email black">support@kinivo.com</a></p>

						<h1>Online support</h1>
						<h2>Chat With Us</h2>
						<p><!-- <a href="" class="button green standar-nowidth">LIVE CHAT</a> -->
							<link href="https://d218iqt4mo6adh.cloudfront.net/assets/widget_embed_191.css" media="screen" rel="stylesheet" type="text/css" />
							<!--If you already have fancybox on the page this script tag should be omitted-->
							<script src="https://d218iqt4mo6adh.cloudfront.net/assets/widget_embed_libraries_191.js" type="text/javascript"></script>

			                <script>
			                        
			                        // ********************************************************************************
			                        // This needs to be placed in the document body where you want the widget to render
			                        // ********************************************************************************
						xmlhttp=new XMLHttpRequest();
						xmlhttp.open("GET", "https://kinivo.com/ktime.php", false);
						xmlhttp.send();
						console.log(xmlhttp.responseText);
						if ( xmlhttp.responseText === "open" ){

			                        new DESK.Widget({ 
			                                version: 1, 
			                                site: 'support.kinivo.com', 
			                                port: '80', 
			                                type: 'chat', 
			                                displayMode: 1,  //0 for popup, 1 for lightbox
			                                features: {  
			                                        offerAlways: true, 
			                                        offerAgentsOnline: false, 
			                                        offerRoutingAgentsAvailable: false,  
			                                        offerEmailIfChatUnavailable: false 
			                                },  
			                                fields: { 
			                                        ticket: { 
			                                                // desc: &#x27;&#x27;,
			                                // labels_new: &#x27;&#x27;,
			                                // priority: &#x27;&#x27;,
			                                // subject: &#x27;&#x27;,
			                                // custom_order_number: &#x27;&#x27;,
			                                // custom_return_issue: &#x27;&#x27;
			                                        }, 
			                                        interaction: { 
			                                                // email: &#x27;&#x27;,
			                                // name: &#x27;&#x27;
			                                        }, 
			                                        chat: { 
			                                                //subject: '' 
			                                        }, 
			                                        customer: { 
			                                                // company: &#x27;&#x27;,
			                                // desc: &#x27;&#x27;,
			                                // first_name: &#x27;&#x27;,
			                                // last_name: &#x27;&#x27;,
			                                // locale_code: &#x27;&#x27;,
			                                // title: &#x27;&#x27;,
			                                // custom_category: &#x27;&#x27;
			                                        } 
			                                } 
			                        }).render();  
							pnums = document.getElementById('pnums');
							cstat = document.getElementById('callstatus');

							pnums.style.color   = "#000000";
							cstat.style.display = "none";
						} else {
							document.write("<p style='color:#aa0000;'>Chat is currently offline.<BR>Chat is available from 9-5 Pacific Time, Mon-Fri.</p>");
						}
			                </script>
						</p>
					</div>
				</div>
			</div>
			<!-- End Content! -->
			<?php get_footer(); ?>