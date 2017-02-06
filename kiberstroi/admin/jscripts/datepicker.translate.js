function Datepicker() {
	this.debug = false; // Change this to true to start debugging
	this._nextId = 0; // Next ID for a date picker instance
	this._inst = []; // List of instances indexed by ID
	this._curInst = null; // The current instance in use
	this._disabledInputs = []; // List of date picker inputs that have been disabled
	this._datepickerShowing = false; // True if the popup picker is showing , false if not
	this._inDialog = false; // True if showing within a "dialog", false if not
	this.regional = []; // Available regional settings, indexed by language code
	this.regional[''] = { // Default regional settings
		clearText: '��������', // Display text for clear link
		clearStatus: '������� ������� ����', // Status text for clear link
		closeText: '�������', // Display text for close link
		closeStatus: '������� ��� ����������', // Status text for close link
		prevText: '&#x3c;����', // Display text for previous month link
		prevStatus: '���������� �����', // Status text for previous month link
		nextText: '����&#x3e;', // Display text for next month link
		nextStatus: '��������� �����', // Status text for next month link
		currentText: '�������', // Display text for current month link
		currentStatus: '������� �����', // Status text for current month link
		monthNames: ['������','�������','����','������','���','����',
			'����','������','��������','�������','������','�������'], // Names of months for drop-down and formatting
		monthNamesShort: ['���', '���', '���', '���', '���', '���', '���', '���', '���', '���', '���', '���'], // For formatting
		monthStatus: '�������� ������ �����', // Status text for selecting a month
		yearStatus: '�������� ������ ���', // Status text for selecting a year
		weekHeader: '���', // Header for the week of the year column
		weekStatus: '������ ����', // Status text for the week of the year column
		dayNames: ['�����������', '�����������', '�������', '�����', '�������', '�������', '�������'], // For formatting
		dayNamesShort: ['���', '���', '���', '���', '���', '���', '���'], // For formatting
		dayNamesMin: ['��','��','��','��','��','��','��'], // Column headings for days starting at Sunday
		dayStatus: '���������� ������ ���� ������', // Status text for the day of the week selection
		dateStatus: '������� ����, �����, ���', // Status text for the date selection
		dateFormat: 'dd.mm.yy', // See format options on parseDate
		firstDay: 1, // The first day of the week, Sun = 0, Mon = 1, ...
		initStatus: '������� ����', // Initial Status text on opening
		isRTL: false // True if right-to-left language, false if left-to-right
	};