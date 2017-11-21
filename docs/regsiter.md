# Register

Expected Request Body: 
```$xslt
{
	"data": {
		"id": "new",
		"type": "accounts",
		"attributes": {
			"firstName": "",
			"lastName": "",
			"emailAddress": "",
			"avatarUri": "",
			"token": ""
		},
		"relationships": {
			"family": {
				"data": {
					"id": "familyUuid",
					"type": "families"
				}
			}
		}
	},
	"included": [{
		"data": {
			"id": "familyUuid",
			"type": "famliies",
			"attributes": {
				"name": "",
				"paymentStrategy": "perChild",
				"completionThreshold": 0,
				"weekStartDay": "Sunday"
			}
		}
	}]
}
```