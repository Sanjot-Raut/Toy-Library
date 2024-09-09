<?php
$uploadDirectory = "/profile_pic/";
								$fileName = basename($_FILES['sr.img']['name']);
								$filePath = $uploadDirectory . $fileName;
								if(move_uploaded_file($_FILES['m_picture']['tmp_name'], $filePath))
									echo "gdfgdf";

                ?>