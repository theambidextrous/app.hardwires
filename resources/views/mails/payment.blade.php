<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>{{ Config::get('app.name') }}</title>
        <style type="text/css">
			#outlook a{padding:0;} 
			.ReadMsgBody{width:100%;} .ExternalClass{width:100%;} 
			.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing */
			body, table, td, p, a, li, blockquote{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
			table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up */
			img{-ms-interpolation-mode:bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */

			/* /\/\/\/\/\/\/\/\/ RESET STYLES /\/\/\/\/\/\/\/\/ */
			body{margin:0; padding:0;}
			img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
			table{border-collapse:collapse !important;}
			body, #bodyTable, #bodyCell{height:100% !important; margin:0; padding:0; width:100% !important;}

			/* /\/\/\/\/\/\/\/\/ TEMPLATE STYLES /\/\/\/\/\/\/\/\/ */

			/* ========== Page Styles ========== */

			#bodyCell{padding:20px;}
			#templateContainer{width:600px;}
			body, #bodyTable{
				background-color:#DEE0E2;
			}
			#bodyCell{
				border-top:4px solid #BBBBBB;
			}

			#templateContainer{
				border:1px solid #BBBBBB;
			}
			
			h1{
				color:#202020 !important;
				display:block;
				font-family:Helvetica;
				font-size:26px;
				font-style:normal;
				font-weight:bold;
				line-height:100%;
				letter-spacing:normal;
				margin-top:0;
				margin-right:0;
				margin-bottom:10px;
				margin-left:0;
				text-align:left;
			}

			
			h2{
				color:#404040 !important;
				display:block;
				font-family:Helvetica;
				font-size:20px;
				font-style:normal;
				font-weight:bold;
				line-height:100%;
				letter-spacing:normal;
				margin-top:0;
				margin-right:0;
				margin-bottom:10px;
				margin-left:0;
				text-align:left;
			}

			
			h3{
				color:#606060 !important;
				display:block;
				font-family:Helvetica;
				font-size:16px;
				font-style:italic;
				font-weight:normal;
				line-height:100%;
				letter-spacing:normal;
				margin-top:0;
				margin-right:0;
				margin-bottom:10px;
				margin-left:0;
				text-align:left;
			}

			
			h4{
				color:#808080 !important;
				display:block;
				font-family:Helvetica;
				font-size:14px;
				font-style:italic;
				font-weight:normal;
				line-height:100%;
				letter-spacing:normal;
				margin-top:0;
				margin-right:0;
				margin-bottom:10px;
				margin-left:0;
				text-align:left;
			}

			/* ========== Header Styles ========== */

			
			#templatePreheader{
				background-color:#F4F4F4;
				border-bottom:1px solid #CCCCCC;
			}

			
			.preheaderContent{
				color:#808080;
				font-family:Helvetica;
				font-size:10px;
				line-height:125%;
				text-align:left;
			}

			
			.preheaderContent a:link, .preheaderContent a:visited, /* Yahoo! Mail Override */ .preheaderContent a .yshortcuts /* Yahoo! Mail Override */{
				color:#606060;
				font-weight:normal;
				text-decoration:underline;
			}

			
			#templateHeader{
				background-color:#F4F4F4;
				border-top:1px solid #FFFFFF;
				border-bottom:1px solid #CCCCCC;
			}

			.headerContent{
				color:#505050;
				font-family:Helvetica;
				font-size:20px;
				font-weight:bold;
				line-height:100%;
				padding-top:0;
				padding-right:0;
				padding-bottom:0;
				padding-left:0;
				text-align:left;
				vertical-align:middle;
			}

			
			.headerContent a:link, .headerContent a:visited, .headerContent a .yshortcuts {
				color:#3464af;
				font-weight:normal;
				text-decoration:underline;
			}

			#headerImage{
				height:auto;
				max-width:600px;
			}

			/* ========== Body Styles ========== */

			
			#templateBody{
				background-color:#F4F4F4;
				border-top:1px solid #FFFFFF;
				border-bottom:1px solid #CCCCCC;
			}

			
			.bodyContent{
				color:#505050;
				font-family:Helvetica;
				font-size:16px;
				line-height:150%;
				padding-top:20px;
				padding-right:20px;
				padding-bottom:20px;
				padding-left:20px;
				text-align:left;
			}

			
			.bodyContent a:link, .bodyContent a:visited,  .bodyContent a .yshortcuts {
				color:#3464af;
				font-weight:normal;
				text-decoration:underline;
			}

			.bodyContent img{
				display:inline;
				height:auto;
				max-width:560px;
			}

			/* ========== Column Styles ========== */

			.templateColumnContainer{width:200px;}

			
			#templateColumns{
				background-color:#F4F4F4;
				border-top:1px solid #FFFFFF;
				border-bottom:1px solid #CCCCCC;
			}

			
			.leftColumnContent{
				color:#505050;
				font-family:Helvetica;
				font-size:14px;
				line-height:150%;
				padding-top:0;
				padding-right:20px;
				padding-bottom:20px;
				padding-left:20px;
				text-align:left;
			}

			
			.leftColumnContent a:link, .leftColumnContent a:visited, /* Yahoo! Mail Override */ .leftColumnContent a .yshortcuts /* Yahoo! Mail Override */{
				color:#3464af;
				font-weight:normal;
				text-decoration:underline;
			}

			
			.centerColumnContent{
				color:#505050;
				font-family:Helvetica;
				font-size:14px;
				line-height:150%;
				padding-top:0;
				padding-right:20px;
				padding-bottom:20px;
				padding-left:20px;
				text-align:left;
			}

			
			.centerColumnContent a:link, .centerColumnContent a:visited, /* Yahoo! Mail Override */ .centerColumnContent a .yshortcuts /* Yahoo! Mail Override */{
				color:#3464af;
				font-weight:normal;
				text-decoration:underline;
			}

			
			.rightColumnContent{
				color:#505050;
				font-family:Helvetica;
				font-size:14px;
				line-height:150%;
				padding-top:0;
				padding-right:20px;
				padding-bottom:20px;
				padding-left:20px;
				text-align:left;
			}

			
			.rightColumnContent a:link, .rightColumnContent a:visited, /* Yahoo! Mail Override */ .rightColumnContent a .yshortcuts /* Yahoo! Mail Override */{
				color:#3464af;
				font-weight:normal;
				text-decoration:underline;
			}

			.leftColumnContent img, .rightColumnContent img{
				display:inline;
				height:auto;
				max-width:260px;
			}

			/* ========== Footer Styles ========== */

			
			#templateFooter{
				background-color:#F4F4F4;
				border-top:1px solid #FFFFFF;
			}

			
			.footerContent{
				color:#808080;
				font-family:Helvetica;
				font-size:10px;
				line-height:150%;
				padding-top:20px;
				padding-right:20px;
				padding-bottom:20px;
				padding-left:20px;
				text-align:left;
			}

			
			.footerContent a:link, .footerContent a:visited, /* Yahoo! Mail Override */ .footerContent a .yshortcuts, .footerContent a span /* Yahoo! Mail Override */{
				color:#606060;
				font-weight:normal;
				text-decoration:underline;
			}

			/* /\/\/\/\/\/\/\/\/ MOBILE STYLES /\/\/\/\/\/\/\/\/ */

            @media only screen and (max-width: 480px){
				/* /\/\/\/\/\/\/ CLIENT-SPECIFIC MOBILE STYLES /\/\/\/\/\/\/ */
				body, table, td, p, a, li, blockquote{-webkit-text-size-adjust:none !important;} /* Prevent Webkit platforms from changing default text sizes */
                body{width:100% !important; min-width:100% !important;} /* Prevent iOS Mail from adding padding to the body */

				/* /\/\/\/\/\/\/ MOBILE RESET STYLES /\/\/\/\/\/\/ */
				#bodyCell{padding:10px !important;}

				/* /\/\/\/\/\/\/ MOBILE TEMPLATE STYLES /\/\/\/\/\/\/ */

				/* ======== Page Styles ======== */

				
				#templateContainer{
					max-width:600px !important;
					width:100% !important;
				}

				
				h1{
					font-size:24px !important;
					line-height:100% !important;
				}

				
				h2{
					font-size:20px !important;
					line-height:100% !important;
				}

				
				h3{
					font-size:18px !important;
					line-height:100% !important;
				}

				
				h4{
					font-size:16px !important;
					line-height:100% !important;
				}

				/* ======== Header Styles ======== */

				#templatePreheader{display:none !important;} /* Hide the template preheader to save space */

				
				#headerImage{
					height:auto !important;
					max-width:600px !important;
					width:100% !important;
				}

				
				.headerContent{
					font-size:20px !important;
					line-height:125% !important;
				}

				/* ======== Body Styles ======== */

				
				.bodyContent{
					font-size:18px !important;
					line-height:125% !important;
				}

				/* ======== Column Styles ======== */

				.templateColumnContainer{display:block !important; width:100% !important;}

				
				.columnImage{
					height:auto !important;
					max-width:480px !important;
					width:100% !important;
				}

				
				.leftColumnContent{
					font-size:16px !important;
					line-height:125% !important;
				}

				
				.centerColumnContent{
					font-size:16px !important;
					line-height:125% !important;
				}

				
				.rightColumnContent{
					font-size:16px !important;
					line-height:125% !important;
				}

				/* ======== Footer Styles ======== */

				
				.footerContent{
					font-size:14px !important;
					line-height:115% !important;
				}

				.footerContent a{display:block !important;} /* Place footer social and utility links on their own lines, for easier access */
			}
		</style>
    </head>
    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
    	<center>
        	<table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
            	<tr>
                	<td align="center" valign="top" id="bodyCell">
                    	<!-- BEGIN TEMPLATE // -->
                    	<table border="0" cellpadding="0" cellspacing="0" id="templateContainer">
                        	<tr>
                            	<td align="center" valign="top">
                                	<!-- BEGIN PREHEADER // -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templatePreheader">
                                        <tr>
                                            <td valign="top" class="preheaderContent" style="padding-top:10px; padding-right:20px; padding-bottom:10px; padding-left:20px;" mc:edit="preheader_content00">
                                            </td>
                                            <!-- *|IFNOT:ARCHIVE_PAGE|* -->
                                            <td valign="top" width="180" class="preheaderContent" style="padding-top:10px; padding-right:20px; padding-bottom:10px; padding-left:0;" mc:edit="preheader_content01">
                                                Need more info? <a href="https://hardwires.co.za/" target="_blank">See our Website</a>
                                            </td>
                                            <!-- *|END:IF|* -->
                                        </tr>
                                    </table>
                                    <!-- // END PREHEADER -->
                                </td>
                            </tr>
                        	<tr>
                            	<td align="center" valign="top">
                                	<!-- BEGIN BODY // -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateBody">
                                        <tr>
                                            <td valign="top" class="bodyContent" mc:edit="body_content">
                                                <h1>{{ $data->title }}</h1>
                                                <b>Dear {{ $data->name }},</b><br><br>
                                                
                                                Thank you for transacting with us. We acknowledge having received payment from you as follows:-<br><br>
                                                Payment Reference: <b>{{ $data->ref }}</b> <br>
                                                Payment Amount: <b>{{ $data->amount }}( {{ $data->zar }} )</b> <br>
                                                Payment Method: <b>{{ $data->method }}</b> <br>

                                                If you have any queries, please email: <a href="mailto:discover@hardwires.co.za">discover@hardwires.co.za</a>

                                                <br><br>
                                                Kind regards
                                                <br>
                                                Hardwires

                                                <br><br>
                                                <h4>This message and any attachment is confidential and may be privileged or otherwise protected from disclosure. If you are not the intended recipient, you may not copy this message or attachment or disclose the contents to any other person. If you have received this transmission in error, please notify the sender immediately and delete the message and any attachment from your system. A2B Transformation Movement does not accept liabilities for any omissions or errors in this message which may arise as a result of email transmission or for damages resulting from any unauthorised changes of the content of this message and any attachments thereto</h4>
                                                
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- // END BODY -->
                                </td>
                            </tr>
                        	<tr>
                            	<td align="center" valign="top">
                                	<!-- BEGIN FOOTER // -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateFooter">
                                        <tr>
                                            <td valign="top" class="footerContent" mc:edit="footer_content00">
                                                <a href="#">Follow on Twitter</a>&nbsp;&nbsp;&nbsp;
                                                <a href="#">Friend on Facebook</a>&nbsp;&nbsp;&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="footerContent" style="padding-top:0;" mc:edit="footer_content01">
                                                <em>Copyright &copy; {{ date('Y') }}, All rights reserved.</em>
                                                <br />
                                               
                                                <br />
                                                <br />
                                                <!-- <strong>Our mailing address is:</strong> -->
                                                <br />
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" class="footerContent" style="padding-top:0;" mc:edit="footer_content02">
                                            	<!-- <a href="#">unsubscribe from this list</a>&nbsp;&nbsp;&nbsp;
                                                <a href="#">update subscription preferences</a>&nbsp; -->
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- // END FOOTER -->
                                </td>
                            </tr>
                        </table>
                        <!-- // END TEMPLATE -->
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>