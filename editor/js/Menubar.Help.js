Menubar.Help = function (editor) {

	var container = new UI.Panel();
	container.setClass('menu');

	var title = new UI.Panel();
	title.setTextContent('选项');
	title.setMargin('0px');
	title.setPadding('8px');
	container.add(title);

	//

	var options = new UI.Panel();
	options.setClass('options');
	container.add(options);

	// source code

	var option = new UI.Panel();
	option.setClass('option');
	option.setTextContent('返回首页');
	option.onClick(function() {
		window.location = "/dimensions/browse.php";
	});
	options.add(option);

	return container;

}
