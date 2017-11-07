## Translator

Translate messages for any project.

#### Install

    `composer require mvaliolahi/translator`
    
##### Usage

Make a directory as resources path, inside the ResourcePath directory make several directory for different languages.
    
###### example: 

1.  `Resources/lang/en` .
2. Inside `en` make a message.php file.
3. The message.php is like below code:

        <?php
    
        return [
            'test' => 'Test',
        ];
        
4. Finally instantiate Validator like below:
    
        $translator = new Translator([
            'locale' => en', 
            'resourcePath' => __DIR__ . '/../src/Resources/lang'
        ]);
5. Use `of` method to find translation in specified language.

        $result = $translator->of('messages.test');
            Output: Test
            
        $result = $translator->of('test');
            Output: Test

    Tips: The messages.php is default translation file, you could define another files as you wish!
           