<?php


/**
 * Интерфейс фигуры
 * Interface FigureInterface
 */
interface FigureInterface
{

    /**
     * Установка позиции/координат фигуры
     * @param array $toPosition - Массив координат
     * @return $this|mixed
     */
    public function setPosition($toPosition);

    /**
     * Масштабирование фигуры
     * @param $scalingFactor - коэффициент масштабирования
     * @return $this|mixed
     */
    public function scale($scalingFactor);

    /**
     * Установка цвета фигуры
     * @param mixed $color - идентификатор цвета фигуры(imageColorAllocate)
     * @return $this
     */
    public function setColor($color);


    /**
     * Поворот фигуры
     * @param integer $angle - Угол поворота
     * @return mixed
     */
    public function rotate($angle);

    /**
     * Рисуем фигуру
     * @param mixed $image - Холст (image resource)
     * @return bool - true/false
     */
    public function draw($image);


}

/**
 * Интерфейс фабрики
 * Interface FactoryInterface
 */
interface FactoryInterface
{
    /**
     * Создание фигуры
     * @param string $type - Тип создаваемой фигуры
     * @return Figure
     */
    public function create($type);
}


/**
 * Фабрика
 * Class FigureFactory
 */
abstract class FigureFactory implements FactoryInterface
{
    /**
     * Создание фигуры
     * @param string $type - Тип создаваемой фигуры
     * @return Figure
     */
    public function create($type)
    {
        switch ($type) {
            case 'Circle':
                return new Circle();
            case 'Triangle':
                return new Triangle();
            case 'Rectangle':
                return new Rectangle();
            default:
                return new Circle();
        }
    }
}


/**
 * Фабрика
 * Class Factory
 */
class Factory extends FigureFactory
{

}

/**
 * Фигура
 * Class Figure
 */
abstract class Figure implements FigureInterface
{
    /**
     * @var string $uid - Уникальный ид созданной фигуры
     */
    public $uid;


    /**
     * @var mixed $color - Цвет
     */
    public $color;

    /**
     * При создании фигуры генер. уникальный id
     */
    public function __construct()
    {
        $this->uid = uniqid();
    }

    /**
     * Поворот фигуры
     * @param integer $angle - Угол поворота
     * @return $this
     */
    public function rotate($angle)
    {
        /*Поворот фигуры*/
        return $this;
    }

    /**
     * Установка цвета
     * @param mixed $color - - идентификатор цвета фигуры(imageColorAllocate) или массив вида [r,g,b]
     * @return $this
     */
    public function setColor($color)
    {
        if (is_array($color) and count($color) == 3) {//Если пришел массив
            $tmpImg = imageCreate(1, 1);//Создаем временный ресурс изображения
            imageJpeg($tmpImg);
            //Получаем идентефикатор цвета
            $this->color = imageColorAllocate($tmpImg, $color[0], $color[1], $color[2]);
            imageDestroy($tmpImg);
        } else {//Если пришел идентификатор
            $this->color = $color;
        }
        return $this;
    }
}


/**
 * Прямоугольник
 * Class Rectangle
 */
class Rectangle extends Figure
{
    /**
     * @var integer $x1 ,$y1,$x2,$y2 - Координаты
     */
    public $x1, $y1, $x2, $y2;

    /**
     * Отрисовка прямоугольника
     * @param mixed $image - Холст (image resource)
     * @return bool - true/false
     */
    public function draw($image)
    {
        return imageFilledRectangle($image, $this->x1, $this->y1, $this->x2, $this->y2, $this->color);
    }


    /**
     * Установка позиции/координат фигуры
     * @param array $toPosition - Массив координат
     * @throws Exception
     * @return $this|mixed
     */
    public function setPosition($toPosition)
    {
        if (count($toPosition) < 4)
            throw new Exception('Not enough parameters. Use: [x1,y1,x2,y2]');
        $this->x1 = $toPosition[0];
        $this->y1 = $toPosition[1];
        $this->x2 = $toPosition[2];
        $this->y2 = $toPosition[3];

        return $this;
    }

    /**
     * Масштабирование фигуры
     * @param integer $scaleFactor - коэффициент масштабирования
     * @return $this|mixed
     */
    public function scale($scaleFactor)
    {
        $this->x1 = $this->x1 - ($scaleFactor * 10);
        $this->y1 = $this->y1 - ($scaleFactor * 10);
        $this->x2 = $this->x2 + ($scaleFactor * 10);
        $this->y2 = $this->y2 + ($scaleFactor * 10);
        return $this;
    }
}

/**
 * Треугольник
 * Class Triangle
 */
class Triangle extends Figure
{
    /**
     * @var integer $x1 , $y1, $x2, $y2, $x3, $y3 - Координаты
     */
    public $x1, $y1, $x2, $y2, $x3, $y3;

    /**
     * Отрисовка треугольника
     * @param mixed $image - Холст (image resource)
     * @return bool - true/false
     */
    public function draw($image)
    {
        /*Отрисовка треугольника*/
        return true;
    }

    /**
     * Установка позиции/координат фигуры
     * @param array $toPosition - Массив координат
     * @return $this|mixed
     */
    public function setPosition($toPosition)
    {
        /*Установка позиции треугольника*/
        return $this;
    }

    /**
     * Масштабирование фигуры
     * @param integer $scaleFactor - коэффициент масштабирования
     * @return $this|mixed
     */
    public function scale($scaleFactor)
    {
        /*Масштабирование треугольника*/
        return $this;
    }
}

/**
 * Круг
 * Class Circle
 */
class Circle extends Figure
{
    /**
     * @var integer $cx ,$cy,$radius - Координаты, радиус
     */
    public $cx, $cy, $radius;

    /**
     * Отрисовка круга
     * @param mixed $image - Холст (image resource)
     * @return bool - true/false
     */
    public function draw($image)
    {
        return imageellipse($image, $this->cx, $this->cy, $this->radius, $this->radius, $this->color);
    }

    /**
     * Установка позиции/координат фигуры
     * @param array $toPosition - Массив координат
     * @throws Exception
     * @return $this|mixed
     */
    public function setPosition($toPosition)
    {
        if (count($toPosition) < 3)
            throw new Exception('Not enough parameters. Use: [cx,cy,radius]');
        $this->cx = $toPosition[0];
        $this->cy = $toPosition[1];
        $this->radius = $toPosition[2];
        return $this;
    }

    /**
     * Масштабирование фигуры
     * @param integer $scaleFactor - коэффициент масштабирования
     * @return $this|mixed
     */
    public function scale($scaleFactor)
    {
        $this->radius = $this->radius + ($scaleFactor * 10);
        return $this;
    }
}


/**
 * Синглтон, дисплей
 * Class Display
 */
class Display
{
    /**
     * @var  $instance
     */
    protected static $instance;

    /**
     * Ширина холста
     */
    const DISPLAY_WIDTH = 800;
    /**
     * Высота холста
     */
    const DISPLAY_HEIGHT = 600;

    /**
     * @var array - Фигура отрисованные на холсте
     */
    public $figures = [];

    /**
     * @var mixed - Холст
     */
    public $display;

    /**
     * Установка цвета фона холста
     * @return mixed
     */
    private function setBackground()
    {
        imageColorAllocate(self::getInstance()->display, 230, 230, 230);
        return self::$instance;
    }

    /**
     * Создаем синглтон
     * @return Display
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;

            /*Создаем холст*/
            self::getInstance()->display = imageCreate(self::DISPLAY_WIDTH, self::DISPLAY_HEIGHT);
            self::getInstance()->setBackground();//Задаем фон холста
        }
        return self::$instance;
    }

    /**
     * Получение идентификатора цвета
     * @param integer $red - Красный
     * @param integer $green - Зеленый
     * @param integer $blue - Синий
     * @return int - идентификатора цвета
     */
    public function color($red, $green, $blue)
    {
        return imageColorAllocate(self::getInstance()->display, $red, $green, $blue);
    }


    /**
     * Вывод на холс всех отрисованных фигур
     * @return mixed
     */
    public function out()
    {

        header("Content-type: image/jpeg");
        imageJpeg(self::getInstance()->display);
        imageDestroy(self::getInstance()->display);
        return self::$instance;
    }

    /**
     * Отрисовка фигуры
     * @param FigureInterface $figure - Фигура
     */
    public function draw(FigureInterface $figure)
    {

        self::getInstance()->figures[$figure->uid] = $figure;
        /** @var Figure $figure */
        $figure->draw(self::getInstance()->display);


    }
}


$factory = new Factory();//Инициируем фабрику

/** @var Rectangle $rectangle */
$rectangle = $factory->create('Rectangle')//Создаем прямоугольник
->setPosition([350, 150, 600,400]);//Задаем координаты
$rectangle->setColor(Display::getInstance()->color(0, 255, 0));//Устанавливаем цвет
$rectangle->scale(2);//Масштабируем прямоугольник

Display::getInstance()->draw($rectangle);//Передаем на вывод


$circle = $factory->create('Circle')//Создаем круг
->setPosition([200, 200, 100])//Задаем координаты
->setColor(Display::getInstance()->color(255, 20, 20))//Устанавливаем цвет
->scale(3);//Масштабируем прямоугольник

Display::getInstance()->draw($circle);//Передаем на вывод


Display::getInstance()->out();//Выводим холст


