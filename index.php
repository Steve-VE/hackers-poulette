<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Hackers Poulette</title>

    <link rel="stylesheet" href="stylesheet/main.css">

</head>
<body>
    <main>
        <section class="left text-center hello">
            <header>
                <h1>Hackers Poulette</h1>
                <h2>Technical support</h2>
            </header>

            <img src="assets/img/logo.png" alt="logo">
            <h2>How can we help you ?</h2>

            <p>How can we improve your experience</p>
        </section>

        <section class="right">
            <form method="post" action="">
                <h1>Contact</h1>
                <p>Fields with an * are mandatory.</p>
                
                <p>
                    <label for="user-firstname">Your name *</label>
                    <input type="text" name="input-user-firstname" id="user-firstname"/>
                </p>
                    
                <p>
                    <label for="user-lastname">Last name *</label>
                    <input type="text" name="input-user-lastname" id="user-lastname"/>
                </p>
                
                <p>
                    <label for="user-email">Email *</label>
                    <input type="email" name="input-user-email" id="user-email"/>
                </p>
                
                <p>
                    <label for="user-country">Country *</label>
                    <select name="select-user-country" id="user-country">
                        <option value="BE">Belgium</option>
                        <option value="FR">France</option>
                    </select>
                </p>

                <p class="together">
                    <input type="radio" name="input-gender" id="gender" value="male" /> Male 
                    <input type="radio" name="input-gender" id="gender" value="female" /> Female
                </p>
                
                <p>
                    <label for="select-subject">Subject</label>
                    <select name="select-subject" id="subject">
                        <option value="hardware">Hardware</option>
                        <option value="software">Software</option>
                        <option value="delivery">Delivery</option>
                        <option value="other">Other</option>
                    </select>
                </p>

                <p>
                    <label for="message">Message *</label>
                    <textarea name="textarea-message" id="message"></textarea>
                </p>
                
                <button type="submit">Submit</button>
            </form>
        </section>
    </main>
</body>
</html>