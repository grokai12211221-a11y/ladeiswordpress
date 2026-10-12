<?php
/**
 * All store page content (English + Arabic) as data.
 *
 * @package LC_Store_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the 9 store pages with slug, title, theme template and bilingual content.
 *
 * @return array[]
 */
function lc_setup_get_pages() {
	return array(
		array(
			'slug'     => 'about',
			'title'    => __( 'About Us', 'lc-store-setup' ),
			'template' => 'page-about.php',
			'en'       => '<h2>Welcome to LadyCorrections — Iraq\'s home for authentic beauty</h2>'
				. '<p>We started LadyCorrections in Baghdad with a simple belief: every woman in Iraq deserves access to 100% original skincare, makeup, hair care and fragrance — at fair prices, delivered to her door.</p>'
				. '<p>Too often, beauty lovers here had to choose between overpriced imports, questionable counterfeits, or waiting weeks for international shipping. We built LadyCorrections to change that. Every product in our store is sourced from authorized distributors and trusted brands, checked for authenticity, and stored with care before it ships to you.</p>'
				. '<p>From daily skincare essentials to statement makeup shades, each item is hand-picked by our team for real skin, real climates, and real routines. We test what we sell, we answer honestly, and we stand behind every single order.</p>'
				. '<h3>Why shop with us?</h3>'
				. '<ul>'
				. '<li><strong>100% authentic</strong> — original products only, sourced from authorized distributors.</li>'
				. '<li><strong>Cash on delivery</strong> — pay when your order arrives, anywhere in Iraq.</li>'
				. '<li><strong>Fair pricing in IQD</strong> — no hidden fees, no surprises at checkout.</li>'
				. '<li><strong>Fast, tracked delivery</strong> — from our door to yours, with order tracking.</li>'
				. '<li><strong>Support in English and Arabic</strong> — a real team, seven days a week.</li>'
				. '</ul>'
				. '<p>Thank you for trusting us with your beauty routine. This is just the beginning.</p>'
				. '<p><em>— The LadyCorrections Team, Baghdad, Iraq</em></p>',
			'ar'       => '<h2>مرحباً بكِ في ليدي كوركشنز — وجهة الجمال الأصيل في العراق</h2>'
				. '<p>بدأنا ليدي كوركشنز في بغداد بإيمان بسيط: كل امرأة في العراق تستحق الحصول على منتجات العناية بالبشرة والمكياج والعناية بالشعر والعطور الأصلية 100% — بأسعار عادلة وتوصيل حتى باب المنزل.</p>'
				. '<p>كثيراً ما كانت عاشقات الجمال هنا مضطرات للاختيار بين منتجات مستوردة بأسعار مبالغ فيها، أو منتجات مقلدة مشكوك فيها، أو انتظار أسابيع للشحن الدولي. أنشأنا ليدي كوركشنز لتغيير ذلك. كل منتج في متجرنا مصدره موزعون معتمدون وعلامات تجارية موثوقة، ويتم التحقق من أصالته وتخزينه بعناية قبل شحنه إليكِ.</p>'
				. '<p>من أساسيات العناية اليومية بالبشرة إلى درجات المكياج الجريئة، كل منتج يختاره فريقنا بعناية ليناسب البشرة الحقيقية والمناخ الحقيقي والروتين اليومي. نجرّب ما نبيعه، ونجيب بصدق، ونقف خلف كل طلب.</p>'
				. '<h3>لماذا تتسوقين معنا؟</h3>'
				. '<ul>'
				. '<li><strong>أصلي 100%</strong> — منتجات أصلية فقط من موزعين معتمدين.</li>'
				. '<li><strong>الدفع عند الاستلام</strong> — ادفعي عند وصول طلبكِ أينما كنتِ في العراق.</li>'
				. '<li><strong>أسعار عادلة بالدينار العراقي</strong> — بلا رسوم خفية ولا مفاجآت.</li>'
				. '<li><strong>توصيل سريع مع تتبع</strong> — من بابنا إلى بابكِ مع تتبع الطلب.</li>'
				. '<li><strong>دعم بالإنجليزية والعربية</strong> — فريق حقيقي سبعة أيام في الأسبوع.</li>'
				. '</ul>'
				. '<p>شكراً لثقتكِ بنا في روتين جمالكِ. وهذه مجرد البداية.</p>'
				. '<p><em>— فريق ليدي كوركشنز، بغداد، العراق</em></p>',
		),
		array(
			'slug'     => 'contact',
			'title'    => __( 'Contact', 'lc-store-setup' ),
			'template' => 'page-contact.php',
			'en'       => '<h2>We\'d love to hear from you</h2>'
				. '<p>Questions about a product, your order, or anything else? Our customer care team replies within 24 hours, seven days a week.</p>'
				. '<ul>'
				. '<li><strong>WhatsApp:</strong> message us anytime for the fastest reply.</li>'
				. '<li><strong>Email:</strong> we read every message and answer personally.</li>'
				. '<li><strong>Hours:</strong> Saturday – Thursday, 9:00 AM – 9:00 PM (Baghdad time).</li>'
				. '</ul>'
				. '<p>For order updates, have your order number ready — you can also track it anytime on our <a href="/track-order/">Track Your Order</a> page.</p>',
			'ar'       => '<h2>يسعدنا سماعكِ</h2>'
				. '<p>هل لديكِ سؤال عن منتج أو طلبكِ أو أي شيء آخر؟ يرد فريق خدمة العملاء خلال 24 ساعة، سبعة أيام في الأسبوع.</p>'
				. '<ul>'
				. '<li><strong>واتساب:</strong> راسلينا في أي وقت للحصول على أسرع رد.</li>'
				. '<li><strong>البريد الإلكتروني:</strong> نقرأ كل رسالة ونجيب عليها شخصياً.</li>'
				. '<li><strong>الدوام:</strong> السبت – الخميس، 9:00 صباحاً – 9:00 مساءً (بتوقيت بغداد).</li>'
				. '</ul>'
				. '<p>للحصول على تحديثات الطلب، جهّزي رقم طلبكِ — ويمكنكِ أيضاً تتبعه في أي وقت من صفحة <a href="/track-order/">تتبع طلبكِ</a>.</p>',
		),
		array(
			'slug'     => 'faq',
			'title'    => __( 'FAQ', 'lc-store-setup' ),
			'template' => 'page-faq.php',
			'en'       => '<h2>Frequently asked questions</h2>'
				. '<h3>1. Are your products 100% authentic?</h3><p>Yes — always. Every product is sourced from authorized distributors and trusted brands. We never sell counterfeits, and every item is quality-checked before dispatch.</p>'
				. '<h3>2. Do you offer Cash on Delivery?</h3><p>Yes! Cash on Delivery is available all across Iraq. You pay in Iraqi Dinar when your order arrives at your door — no prepayment needed.</p>'
				. '<h3>3. How long does delivery take?</h3><p>Orders are processed within 24 hours. Delivery takes 1–3 working days inside Baghdad and 2–5 working days for other governorates.</p>'
				. '<h3>4. What are the delivery charges?</h3><p>Standard delivery is a flat 5,000 IQD anywhere in Iraq — and it\'s <strong>free</strong> on all orders over 50,000 IQD.</p>'
				. '<h3>5. Can I return or exchange a product?</h3><p>Yes, within 7 days of delivery, as long as the product is unopened and unused with seals intact. For hygiene reasons, opened makeup and personal-care items cannot be returned. See our <a href="/returns-refunds/">Returns &amp; Refunds</a> page for details.</p>'
				. '<h3>6. How do I track my order?</h3><p>Enter your order number and email on our <a href="/track-order/">Track Your Order</a> page to see live status updates.</p>'
				. '<h3>7. Do you ship outside Iraq?</h3><p>Currently we deliver within Iraq only. Follow our newsletter for announcements about new delivery areas.</p>'
				. '<h3>8. How can I contact customer support?</h3><p>Message us on WhatsApp for the fastest reply, or reach us via the <a href="/contact/">Contact</a> page. We reply within 24 hours, seven days a week.</p>',
			'ar'       => '<h2>الأسئلة الشائعة</h2>'
				. '<h3>1. هل منتجاتكم أصلية 100%؟</h3><p>نعم — دائماً. كل منتج مصدره موزعون معتمدون وعلامات تجارية موثوقة. لا نبيع المنتجات المقلدة أبداً، ويتم فحص كل منتج قبل الشحن.</p>'
				. '<h3>2. هل يتوفر الدفع عند الاستلام؟</h3><p>نعم! الدفع عند الاستلام متوفر في جميع أنحاء العراق. تدفعين بالدينار العراقي عند وصول طلبكِ إلى باب منزلكِ — دون الحاجة لأي دفع مسبق.</p>'
				. '<h3>3. كم تستغرق مدة التوصيل؟</h3><p>تتم معالجة الطلبات خلال 24 ساعة. يستغرق التوصيل 1–3 أيام عمل داخل بغداد و2–5 أيام عمل لبقية المحافظات.</p>'
				. '<h3>4. ما هي رسوم التوصيل؟</h3><p>التوصيل العادي بسعر ثابت 5,000 دينار عراقي لأي مكان في العراق — وهو <strong>مجاني</strong> لجميع الطلبات فوق 50,000 دينار عراقي.</p>'
				. '<h3>5. هل يمكنني إرجاع أو استبدال منتج؟</h3><p>نعم، خلال 7 أيام من الاستلام، بشرط أن يكون المنتج غير مفتوح وغير مستخدم والأختام سليمة. لأسباب صحية، لا يمكن إرجاع مستحضرات التجميل المفتوحة. راجعي صفحة <a href="/returns-refunds/">الإرجاع والاسترداد</a> للتفاصيل.</p>'
				. '<h3>6. كيف أتتبع طلبي؟</h3><p>أدخلي رقم الطلب وبريدكِ الإلكتروني في صفحة <a href="/track-order/">تتبع طلبكِ</a> لرؤية تحديثات الحالة مباشرة.</p>'
				. '<h3>7. هل تشحنون خارج العراق؟</h3><p>حالياً نوصل داخل العراق فقط. تابعي نشرتنا البريدية لإعلانات مناطق التوصيل الجديدة.</p>'
				. '<h3>8. كيف أتواصل مع خدمة العملاء؟</h3><p>راسلينا على واتساب للحصول على أسرع رد، أو عبر صفحة <a href="/contact/">اتصل بنا</a>. نرد خلال 24 ساعة، سبعة أيام في الأسبوع.</p>',
		),
		array(
			'slug'     => 'shipping-info',
			'title'    => __( 'Shipping & Delivery', 'lc-store-setup' ),
			'template' => 'page-info.php',
			'en'       => '<h2>Shipping &amp; Delivery</h2>'
				. '<h3>Processing time</h3><p>Orders placed before 3:00 PM (Baghdad time) are processed the same working day. Orders placed later, or on Fridays, are processed the next working day.</p>'
				. '<h3>Delivery times</h3><ul><li><strong>Baghdad:</strong> 1–3 working days</li><li><strong>Other governorates:</strong> 2–5 working days</li></ul>'
				. '<h3>Delivery charges</h3><ul><li><strong>Standard delivery:</strong> flat 5,000 IQD anywhere in Iraq</li><li><strong>Free delivery:</strong> on all orders over 50,000 IQD</li></ul>'
				. '<h3>Cash on Delivery</h3><p>Pay in Iraqi Dinar when your order arrives. Please keep the exact amount ready if possible, and make sure someone is available to receive the order.</p>'
				. '<h3>Delays</h3><p>During sales, holidays, or severe weather, delivery may take 1–2 extra days. We will always notify you if your order is delayed. You can follow your parcel anytime on our <a href="/track-order/">Track Your Order</a> page.</p>',
			'ar'       => '<h2>الشحن والتوصيل</h2>'
				. '<h3>مدة المعالجة</h3><p>تتم معالجة الطلبات المقدمة قبل الساعة 3:00 ظهراً (بتوقيت بغداد) في نفس يوم العمل. أما الطلبات المقدمة بعد ذلك أو يوم الجمعة فتتم معالجتها في يوم العمل التالي.</p>'
				. '<h3>مدد التوصيل</h3><ul><li><strong>بغداد:</strong> 1–3 أيام عمل</li><li><strong>بقية المحافظات:</strong> 2–5 أيام عمل</li></ul>'
				. '<h3>رسوم التوصيل</h3><ul><li><strong>التوصيل العادي:</strong> 5,000 دينار عراقي ثابت لأي مكان في العراق</li><li><strong>التوصيل المجاني:</strong> لجميع الطلبات فوق 50,000 دينار عراقي</li></ul>'
				. '<h3>الدفع عند الاستلام</h3><p>ادفعي بالدينار العراقي عند وصول طلبكِ. يرجى تجهيز المبلغ إن أمكن والتأكد من وجود من يستلم الطلب.</p>'
				. '<h3>التأخير</h3><p>خلال التخفيضات والعطل أو سوء الأحوال الجوية قد يتأخر التوصيل 1–2 أيام إضافية. سنعلمكِ دائماً عند تأخر طلبكِ، ويمكنكِ متابعة طردكِ في أي وقت من صفحة <a href="/track-order/">تتبع طلبكِ</a>.</p>',
		),
		array(
			'slug'     => 'returns-refunds',
			'title'    => __( 'Returns & Refunds', 'lc-store-setup' ),
			'template' => 'page-info.php',
			'en'       => '<h2>Returns &amp; Refunds</h2>'
				. '<p>We want you to love what you buy. If something isn\'t right, you may return it within <strong>7 days of delivery</strong>.</p>'
				. '<h3>Conditions</h3><ul><li>The product must be <strong>unopened, unused</strong>, with all seals and packaging intact.</li><li>For hygiene and safety reasons, <strong>opened makeup, lipsticks, mascaras and personal-care items cannot be returned</strong>.</li><li>Please include your order number with the return.</li></ul>'
				. '<h3>Damaged or wrong items</h3><p>If your order arrived damaged, defective, or incorrect, contact us within 48 hours with photos — we will replace it or refund you in full, including delivery charges, no questions asked.</p>'
				. '<h3>How to start a return</h3><p>Message us on WhatsApp or via the <a href="/contact/">Contact</a> page with your order number. Our team will arrange the pickup and confirm your refund.</p>'
				. '<h3>Refunds</h3><p>Approved refunds are issued within 5–7 working days via bank transfer or store credit — your choice. Delivery charges are refunded only when the return is due to our error.</p>',
			'ar'       => '<h2>الإرجاع والاسترداد</h2>'
				. '<p>نريدكِ أن تحبي ما تشترينه. إذا كان هناك أي مشكلة، يمكنكِ إرجاع المنتج خلال <strong>7 أيام من الاستلام</strong>.</p>'
				. '<h3>الشروط</h3><ul><li>يجب أن يكون المنتج <strong>غير مفتوح وغير مستخدم</strong> وجميع الأختام والتغليف سليمة.</li><li>لأسباب صحية، <strong>لا يمكن إرجاع مستحضرات التجميل المفتوحة وأحمر الشفاه والماسكارا ومنتجات العناية الشخصية</strong>.</li><li>يرجى إرفاق رقم الطلب مع المرتجع.</li></ul>'
				. '<h3>المنتجات التالفة أو الخاطئة</h3><p>إذا وصل طلبكِ تالفاً أو معيباً أو خاطئاً، تواصلي معنا خلال 48 ساعة مع الصور — سنستبدله أو نرد لكِ المبلغ كاملاً بما فيه رسوم التوصيل، دون أي أسئلة.</p>'
				. '<h3>كيف تبدئين الإرجاع؟</h3><p>راسلينا على واتساب أو عبر صفحة <a href="/contact/">اتصل بنا</a> مع رقم الطلب، وسيرتب فريقنا الاستلام ويؤكد استردادكِ.</p>'
				. '<h3>الاسترداد</h3><p>تتم عمليات الاسترداد المعتمدة خلال 5–7 أيام عمل عبر التحويل البنكي أو رصيد المتجر — حسب اختياركِ. تُسترد رسوم التوصيل فقط عندما يكون الإرجاع بسبب خطأ منا.</p>',
		),
		array(
			'slug'     => 'privacy-policy',
			'title'    => __( 'Privacy Policy', 'lc-store-setup' ),
			'template' => 'page-info.php',
			'en'       => '<h2>Privacy Policy</h2><p><em>Last updated: 2026</em></p>'
				. '<p>LadyCorrections respects your privacy. This policy explains what data we collect and how we use it.</p>'
				. '<h3>What we collect</h3><ul><li><strong>Order information:</strong> name, phone number, delivery address, and order details — needed to deliver your purchase.</li><li><strong>Account information:</strong> email and password if you create an account.</li><li><strong>Browsing data:</strong> cookies and basic analytics to improve our store.</li></ul>'
				. '<h3>How we use it</h3><p>We use your data only to process and deliver orders, provide customer support, and — with your consent — send offers and updates. We never sell your personal information to third parties.</p>'
				. '<h3>Sharing</h3><p>We share only what is necessary with delivery couriers (name, phone, address) so your order can reach you, and with payment providers when you pay online.</p>'
				. '<h3>Cookies</h3><p>We use cookies to keep your cart, remember your preferences, and understand store traffic. You can disable cookies in your browser, though some features may stop working.</p>'
				. '<h3>Your rights</h3><p>You may request a copy, correction, or deletion of your personal data at any time by contacting us via the <a href="/contact/">Contact</a> page.</p>'
				. '<h3>Security</h3><p>Our site uses SSL encryption, and access to customer data is restricted to authorized staff only.</p>',
			'ar'       => '<h2>سياسة الخصوصية</h2><p><em>آخر تحديث: 2026</em></p>'
				. '<p>تحترم ليدي كوركشنز خصوصيتكِ. توضح هذه السياسة البيانات التي نجمعها وكيفية استخدامها.</p>'
				. '<h3>ما الذي نجمعه؟</h3><ul><li><strong>معلومات الطلب:</strong> الاسم ورقم الهاتف وعنوان التوصيل وتفاصيل الطلب — اللازمة لتوصيل مشترياتكِ.</li><li><strong>معلومات الحساب:</strong> البريد الإلكتروني وكلمة المرور عند إنشاء حساب.</li><li><strong>بيانات التصفح:</strong> ملفات تعريف الارتباط وإحصاءات أساسية لتحسين متجرنا.</li></ul>'
				. '<h3>كيف نستخدمها؟</h3><p>نستخدم بياناتكِ فقط لمعالجة الطلبات وتوصيلها وتقديم خدمة العملاء — وبموافقتكِ لإرسال العروض والتحديثات. لا نبيع معلوماتكِ الشخصية لأي طرف ثالث أبداً.</p>'
				. '<h3>المشاركة</h3><p>نشارك فقط ما هو ضروري مع شركات التوصيل (الاسم والهاتف والعنوان) ليصل طلبكِ إليكِ، ومع مزودي الدفع عند الدفع إلكترونياً.</p>'
				. '<h3>ملفات تعريف الارتباط</h3><p>نستخدم ملفات تعريف الارتباط للحفاظ على سلة التسوق وتذكر تفضيلاتكِ وفهم حركة المتجر. يمكنكِ تعطيلها من المتصفح، لكن بعض المزايا قد تتوقف عن العمل.</p>'
				. '<h3>حقوقكِ</h3><p>يمكنكِ طلب نسخة من بياناتكِ الشخصية أو تصحيحها أو حذفها في أي وقت عبر صفحة <a href="/contact/">اتصل بنا</a>.</p>'
				. '<h3>الأمان</h3><p>يستخدم موقعنا تشفير SSL، والوصول إلى بيانات العملاء مقتصر على الموظفين المخولين فقط.</p>',
		),
		array(
			'slug'     => 'terms-conditions',
			'title'    => __( 'Terms & Conditions', 'lc-store-setup' ),
			'template' => 'page-info.php',
			'en'       => '<h2>Terms &amp; Conditions</h2><p><em>Last updated: 2026</em></p>'
				. '<p>By using LadyCorrections, you agree to the following terms.</p>'
				. '<h3>Products &amp; pricing</h3><p>All products are 100% authentic. Prices are listed in Iraqi Dinar (IQD) and include applicable taxes. We reserve the right to correct pricing errors and to change prices at any time.</p>'
				. '<h3>Orders</h3><p>Placing an order is an offer to purchase. We confirm every order by phone or WhatsApp before dispatch; we may cancel orders with incorrect details or suspected fraud.</p>'
				. '<h3>Payment</h3><p>We accept Cash on Delivery across Iraq and online payment where available. For COD orders, please ensure someone is available to receive and pay for the order.</p>'
				. '<h3>Shipping &amp; returns</h3><p>Delivery times and charges are described on our <a href="/shipping-info/">Shipping &amp; Delivery</a> page. Returns follow our <a href="/returns-refunds/">Returns &amp; Refunds</a> policy.</p>'
				. '<h3>Accounts</h3><p>You are responsible for keeping your account credentials confidential. We may suspend accounts involved in abuse or fraud.</p>'
				. '<h3>Intellectual property</h3><p>All site content — text, images, logos — belongs to LadyCorrections and may not be copied without permission.</p>'
				. '<h3>Liability</h3><p>We are not liable for indirect damages arising from use of our products beyond the purchase price. Always patch-test cosmetics before full use.</p>'
				. '<h3>Contact</h3><p>Questions about these terms? Reach us via the <a href="/contact/">Contact</a> page.</p>',
			'ar'       => '<h2>الشروط والأحكام</h2><p><em>آخر تحديث: 2026</em></p>'
				. '<p>باستخدامكِ ليدي كوركشنز فإنكِ توافقين على الشروط التالية.</p>'
				. '<h3>المنتجات والأسعار</h3><p>جميع المنتجات أصلية 100%. الأسعار بالدينار العراقي وتشمل الضرائب المطبقة. نحتفظ بحق تصحيح أخطاء الأسعار وتغييرها في أي وقت.</p>'
				. '<h3>الطلبات</h3><p>تقديم الطلب هو عرض للشراء. نؤكد كل طلب عبر الهاتف أو واتساب قبل الشحن، وقد نلغي الطلبات ذات البيانات غير الصحيحة أو المشتبه فيها.</p>'
				. '<h3>الدفع</h3><p>نقبل الدفع عند الاستلام في جميع أنحاء العراق والدفع الإلكتروني حيثما توفر. لطلبات الدفع عند الاستلام، يرجى التأكد من وجود من يستلم الطلب ويدفع ثمنه.</p>'
				. '<h3>الشحن والإرجاع</h3><p>مدد التوصيل ورسومه موضحة في صفحة <a href="/shipping-info/">الشحن والتوصيل</a>. ويخضع الإرجاع لسياسة <a href="/returns-refunds/">الإرجاع والاسترداد</a>.</p>'
				. '<h3>الحسابات</h3><p>أنتِ مسؤولة عن الحفاظ على سرية بيانات حسابكِ. وقد نعلّق الحسابات المتورطة في إساءة الاستخدام أو الاحتيال.</p>'
				. '<h3>الملكية الفكرية</h3><p>جميع محتويات الموقع — النصوص والصور والشعارات — ملك ليدي كوركشنز ولا يجوز نسخها دون إذن.</p>'
				. '<h3>المسؤولية</h3><p>لا نتحمل مسؤولية الأضرار غير المباشرة الناشئة عن استخدام منتجاتنا بما يتجاوز سعر الشراء. اختبري مستحضرات التجميل على جزء صغير من البشرة قبل الاستخدام الكامل دائماً.</p>'
				. '<h3>تواصلي معنا</h3><p>أسئلة حول هذه الشروط؟ تواصلي معنا عبر صفحة <a href="/contact/">اتصل بنا</a>.</p>',
		),
		array(
			'slug'     => 'track-order',
			'title'    => __( 'Track Your Order', 'lc-store-setup' ),
			'template' => 'page-track.php',
			'en'       => '<h2>Track your order</h2>'
				. '<p>Enter your <strong>order number</strong> and the <strong>email address</strong> you used at checkout below to see live updates — from processing to out-for-delivery.</p>'
				. '<p>Your order number was sent to you by email and WhatsApp right after checkout. It looks like <code>#1234</code>.</p>'
				. '<p><em>Tip: statuses move from <strong>Pending</strong> → <strong>Confirmed</strong> → <strong>Shipped</strong> → <strong>Delivered</strong>.</em></p>',
			'ar'       => '<h2>تتبعي طلبكِ</h2>'
				. '<p>أدخلي <strong>رقم الطلب</strong> و<strong>البريد الإلكتروني</strong> المستخدم عند إتمام الشراء بالأسفل لرؤية التحديثات المباشرة — من المعالجة حتى التوصيل.</p>'
				. '<p>تم إرسال رقم طلبكِ إليكِ عبر البريد الإلكتروني وواتساب مباشرة بعد إتمام الشراء، ويبدو مثل <code>#1234</code>.</p>'
				. '<p><em>ملاحظة: تنتقل الحالات من <strong>قيد الانتظار</strong> ← <strong>مؤكد</strong> ← <strong>تم الشحن</strong> ← <strong>تم التوصيل</strong>.</em></p>',
		),
		array(
			'slug'     => 'wishlist',
			'title'    => __( 'Wishlist', 'lc-store-setup' ),
			'template' => 'page-wishlist.php',
			'en'       => '<h2>Your wishlist</h2>'
				. '<p>Tap the <strong>heart icon</strong> on any product to save it here for later. Your wishlist is kept on this device, so your favorites are always one tap away.</p>'
				. '<p>Ready to shop? <a href="/shop/">Browse the collection</a> and start saving the pieces you love.</p>',
			'ar'       => '<h2>قائمة أمنياتكِ</h2>'
				. '<p>اضغطي على أيقونة <strong>القلب</strong> في أي منتج لحفظه هنا لوقت لاحق. تُحفظ قائمة أمنياتكِ على هذا الجهاز، لتكون منتجاتكِ المفضلة على بعد ضغطة واحدة دائماً.</p>'
				. '<p>جاهزة للتسوق؟ <a href="/shop/">تصفحي المجموعة</a> وابدئي بحفظ القطع التي تعجبكِ.</p>',
		),
	);
}

/**
 * Combine EN + AR content with a divider.
 *
 * @param array $page Page definition from lc_setup_get_pages().
 * @return string
 */
function lc_setup_build_content( $page ) {
	$html  = '<div class="lc-setup-en">' . $page['en'] . '</div>';
	$html .= '<hr class="lc-setup-divider" />';
	$html .= '<div class="lc-setup-ar" dir="rtl" lang="ar">' . $page['ar'] . '</div>';
	return $html;
}
