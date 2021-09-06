<?php


namespace AcMarche\Booking\Lib;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Extra\Intl\IntlExtension;
use Twig\Extra\String\StringExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class Twig
{
    public static function LoadTwig(?string $path = null): Environment
    {
        //todo get instance
        if (!$path) {
            $path = plugin_dir_path(__DIR__).'/templates';
        }

        $loader = new FilesystemLoader($path);

        $environment = new Environment(
            $loader,
            [
                'cache' => ABSPATH.'var/cache',
                'debug' => WP_DEBUG,
                'strict_variables' => WP_DEBUG,
            ]
        );

        // wp_get_environment_type();
        if (WP_DEBUG) {
            $environment->addExtension(new DebugExtension());
            $environment->addExtension(new StringExtension());
            $environment->addExtension(new IntlExtension());
        }

        $environment->addGlobal('template_directory', get_template_directory_uri());
        $environment->addFunction(self::showTemplate());

        return $environment;
    }

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     */
    public function render(string $templatePath, array $variables): string
    {
        $twig = self::LoadTwig();

        return $twig->render(
            $templatePath,
            $variables,
        );
    }

    public static function rendPage(string $templatePath, array $variables = []): string
    {
        $twig = self::LoadTwig();
        try {
            return $twig->render(
                $templatePath,
                $variables,
            );
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            wp_mail('webmaster@marche.be', 'Esquare erreur agenda', $e->getMessage());

            return $twig->render(
                '_error_500.html.twig',
                [

                ]
            );
        }
    }


    protected static function showTemplate(): TwigFunction
    {
        return new TwigFunction(
            'showTemplate',
            function (): string {
                if (true === WP_DEBUG) {
                    global $template;

                    return 'template: '.$template;
                }

                return '';
            }
        );
    }


}
