<?php
namespace Fardin\Gent\Widgets;

if (!defined("ABSPATH")) {
	exit;
}
use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;
use \Elementor\Icons_Manager;

class BasicWidget extends Widget_Base
{

	use \Fardin\Gent\App\Traits\Singletion;

	public function get_name(): string
	{
		return 'ele_button';
	}

	public function get_title(): string
	{
		return esc_html__('ELE Button', ELE_ADDONS_TEXT_DOMAIN);
	}

	public function get_icon(): string
	{
		return 'eicon-button';
	}

	public function get_categories(): array
	{
		return ['basic'];
	}

	public function get_keywords(): array
	{
		return ['addon', 'ele-addon', 'ele-button', 'button'];
	}

	protected function register_controls(): void
	{
		$this->content_controls_section();
		$this->style_controls_section();



	}
	protected function content_controls_section()
	{
		// Content Tab Start

		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__('Advance Button ', ELE_ADDONS_TEXT_DOMAIN),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__('Text', ELE_ADDONS_TEXT_DOMAIN),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('Click me', ELE_ADDONS_TEXT_DOMAIN),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		$this->add_control(
			'button_link',
			[
				'label' => esc_html__('link', ELE_ADDONS_TEXT_DOMAIN),
				'type' => Controls_Manager::URL,
				'options' => ['url', 'is_external', 'nofollow'],
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
					// 'custom_attributes' => '',
				],
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'show_icon',
			[
				'label' => esc_html__('Add Icon', 'textdomain'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'textdomain'),
				'label_off' => esc_html__('Hide', 'textdomain'),
				'return_value' => 'yes',
				'default' => 'hide',
			]
		);

		$this->add_control(
			'button_icon',
			[
				'label' => esc_html__('Icon', 'ele-addons'),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-arrow-right',
					'library' => 'fa-solid',
				],
				'condition' => [
					'show_icon' => ['yes'],
				],
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label' => esc_html__('Icon Position', 'textdomain'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'row' => [
						'title' => esc_html__('Start', 'textdomain'),
						'icon' => 'eicon-h-align-left',
					],

					'row-reverse' => [
						'title' => esc_html__('End', 'textdomain'),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'row',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .ele_addons_advance_button_wrapper' => 'flex-direction: {{VALUE}};',
				],
				'condition' => [
					'show_icon' => ['yes'],
				],
			]
		);

		$this->add_control(
			'icon_spacing',
			[
				'label' => esc_html__('Icon Spacing', 'textdomain'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'rem', 'custom'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .ele_addons_advance_button_wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_icon' => ['yes'],
				],
			]
		);


		$this->end_controls_section();

		// Content Tab End
	}
	protected function style_controls_section()
	{
		// Style Tab Start
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__('Button Style', ELE_ADDONS_TEXT_DOMAIN),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_position',
			[
				'label' => esc_html__('Position', 'textdomain'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'textdomain'),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'textdomain'),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'textdomain'),
						'icon' => 'eicon-h-align-right',
					],
					'stretch' => [
						'title' => esc_html__('Stretch', 'textdomain'),
						'icon' => 'eicon-h-align-stretch',
					],
				],
				'default' => 'center',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .your-class' => 'text-align: {{VALUE}};',
				],
			]
		);



		$this->add_control(
			'',
			[
				'label' => esc_html__('Text Color', ELE_ADDONS_TEXT_DOMAIN),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hello-world' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Tab End
	}

	protected function render(): void
	{
		$settings = $this->get_settings_for_display();

		if (empty($settings['button_text'])) {
			return;
		}
		if (!empty($settings['button_link']['url'])) {
			$this->add_link_attributes('button_link', $settings['button_link']);
		}
		?>

		<div class="ele_addons_advance_button_container">
			<a <?php $this->print_render_attribute_string('button_link') ?> class="ele_addons_advance_button elementor-button">
			<span class="ele_addons_advance_button_wrapper">
				<span class="ele_addons_icon_wrapper">
					<?php Icons_Manager::render_icon($settings['button_icon'], ['arial-hidden' => 'true']); ?>
				</span>
				<span>
					<?php echo $settings['button_text']; ?>
				</span>
			</span>

		</a>
		</div>
		<?php
	}
}