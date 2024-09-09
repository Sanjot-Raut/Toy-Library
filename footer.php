<style>
 .footer {
    background-color: #333;
    color: #fff;
    padding: 2px 0;
    font-size: 14px;
   
		text-align: center;
		padding: 20px 0;
		position: fixed;
		bottom: 0;
		width: 100%;
}

.footer .container {
    display: flex;
    justify-content: center;
    align-items: center;
}

.footer-content {
    display: flex;
    justify-content: space-around;
    width: 100%; /* Adjust the width as needed */
    margin: 0 auto;
    
}


.footer-section.about h2 {
    font-size: 16px;
    margin-bottom: 0px;
    padding: 0px;
}

.footer-section.about p {
    margin-bottom: 2px;
}


.footer-bottom {
    text-align: center;
    padding-top: 2px;
}

</style>
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section about">
                <h2>About Toy Library</h2>
                <p>Located at Mayur Colony, Kuthrud, Pune 412365 | Email: toylibrary@gmail.com | Phone: +91 1234567890</p>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; <?php echo date("Y"); ?> Toy Library. All Rights Reserved.
    </div>
</footer>
