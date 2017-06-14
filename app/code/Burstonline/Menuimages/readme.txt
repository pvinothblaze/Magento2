Place this code in your layout/default.xml

<referenceContainer name="header.container">
			<update handle="default_head_blocks"/>
			<referenceBlock name="catalog.topnav" class="Burstonline\Menuimages\Block\Html\Topmenu" template="Magento_Theme::html/topmenu.phtml"/>
		</referenceContainer>
