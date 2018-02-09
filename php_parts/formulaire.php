<section class="right">
            <form method="post" action="">
                <h1>Contact</h1>
                <p>Fields with an * are mandatory.</p>
                
                <?php  
                create_input( "user-firstname", "Your name" );
                create_input( "user-lastname", "Last name" );
                create_input( "email", "Email", "email"  );

                create_input( "user-country", "Country", "select", true, country_list() );

                create_input( "gender", null, "radio", false, array("male", "female") );

                create_input( "subject", "Subject", "select", false, array("hardware", "software", "delivery", "other") );

                create_input( "message", "Message", "textarea" );
                ?>
                
                <button type="submit">Submit</button>
            </form>
        </section>